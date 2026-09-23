<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $role = $request->string('role')->trim()->toString();

        $users = User::query()
            ->withCount('borrowRecords')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role !== '', fn ($q) => $q->where('role', $role))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        app(ActivityLogService::class)->log('user.created', "เพิ่มผู้ใช้ใหม่: {$user->name} ({$user->email})", $user);

        return redirect()->route('admin.users.index')->with('success', "เพิ่มผู้ใช้ {$user->name} เรียบร้อยแล้ว");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        app(ActivityLogService::class)->log('user.updated', "แก้ไขข้อมูลผู้ใช้: {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', "อัปเดตข้อมูลคุณ {$user->name} เรียบร้อยแล้ว");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบผู้ใช้งานที่กำลังเข้าสู่ระบบอยู่ได้');
        }

        $activeLoans = $user->borrowRecords()->whereIn('status', ['borrowed', 'overdue', 'pending'])->count();
        if ($activeLoans > 0) {
            return back()->with('error', 'ไม่สามารถลบผู้ใช้ท่านนี้ได้ เนื่องจากมีรายการยืมหนังสือที่ยังไม่ได้คืนหรือค้างอนุมัติอยู่');
        }

        $name = $user->name;
        $user->delete();

        app(ActivityLogService::class)->log('user.deleted', "ลบผู้ใช้งาน: {$name}");

        return redirect()->route('admin.users.index')->with('success', "ลบผู้ใช้ {$name} เรียบร้อยแล้ว");
    }
}
