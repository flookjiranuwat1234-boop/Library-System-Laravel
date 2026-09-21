<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $action = $request->string('action')->trim()->toString();
        $userId = $request->integer('user_id');

        $logs = ActivityLog::query()
            ->with('user')
            ->when($action !== '', fn ($q) => $q->where('action', 'like', "%{$action}%"))
            ->when($userId > 0, fn ($q) => $q->where('user_id', $userId))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.activity-logs', compact('logs', 'users', 'action', 'userId'));
    }
}
