<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BadgeAdminController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.journey.index');
    }

    public function create(): View
    {
        return view('admin.badges.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:badges,code',
            'name_th' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'icon' => 'required|string|max:10',
            'tier' => 'required|in:bronze,silver,gold,special',
            'condition_type' => 'required|in:books_read,categories_explored,on_time_streak_weeks,manual',
            'condition_value' => 'required|integer|min:0',
            'sort_order' => 'required|integer|min:0',
        ]);

        Badge::create($validated);

        return redirect()->route('admin.journey.index')->with('success', 'สร้างเหรียญรางวัลใหม่เรียบร้อยแล้ว');
    }

    public function edit(Badge $badge): View
    {
        return view('admin.badges.edit', [
            'badge' => $badge,
        ]);
    }

    public function update(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:badges,code,'.$badge->id,
            'name_th' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'icon' => 'required|string|max:10',
            'tier' => 'required|in:bronze,silver,gold,special',
            'condition_type' => 'required|in:books_read,categories_explored,on_time_streak_weeks,manual',
            'condition_value' => 'required|integer|min:0',
            'sort_order' => 'required|integer|min:0',
        ]);

        $badge->update($validated);

        return redirect()->route('admin.journey.index')->with('success', "อัปเดตข้อมูลเหรียญ '{$badge->name_th}' เรียบร้อยแล้ว");
    }

    public function destroy(Badge $badge): RedirectResponse
    {
        $badge->users()->detach();
        $badge->delete();

        return redirect()->route('admin.journey.index')->with('success', 'ลบเหรียญรางวัลเรียบร้อยแล้ว');
    }
}
