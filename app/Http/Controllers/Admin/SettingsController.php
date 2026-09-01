<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Notifications\TestEmailNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'lowStockThreshold' => Setting::valueFor('low_stock_threshold', config('library.low_stock_threshold')),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'low_stock_threshold' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        Setting::put('low_stock_threshold', $validated['low_stock_threshold']);

        return back()->with('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }

    public function testEmail(Request $request): RedirectResponse
    {
        $request->user()->notify((new TestEmailNotification)->afterCommit());

        return back()->with('success', 'จัดคิวอีเมลทดสอบแล้ว กรุณาตรวจสอบกล่องข้อความ');
    }
}
