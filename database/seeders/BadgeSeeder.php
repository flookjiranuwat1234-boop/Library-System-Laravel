<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'code' => 'first_book',
                'name_th' => 'ก้าวแรกของนักอ่าน',
                'description' => 'อ่านและส่งคืนหนังสือเล่มแรกสำเร็จ',
                'icon' => '🌱',
                'tier' => 'bronze',
                'condition_type' => 'books_read',
                'condition_value' => 1,
                'sort_order' => 1,
            ],
            [
                'code' => 'bookworm_5',
                'name_th' => 'หนอนหนังสือ',
                'description' => 'อ่านและส่งคืนหนังสือครบ 5 เล่ม',
                'icon' => '🐛',
                'tier' => 'bronze',
                'condition_type' => 'books_read',
                'condition_value' => 5,
                'sort_order' => 2,
            ],
            [
                'code' => 'bookworm_20',
                'name_th' => 'นักอ่านตัวยง',
                'description' => 'อ่านและส่งคืนหนังสือครบ 20 เล่ม',
                'icon' => '📖',
                'tier' => 'silver',
                'condition_type' => 'books_read',
                'condition_value' => 20,
                'sort_order' => 3,
            ],
            [
                'code' => 'bookworm_50',
                'name_th' => 'ปราชญ์แห่งห้องสมุด',
                'description' => 'อ่านและส่งคืนหนังสือครบ 50 เล่ม',
                'icon' => '🏛️',
                'tier' => 'gold',
                'condition_type' => 'books_read',
                'condition_value' => 50,
                'sort_order' => 4,
            ],
            [
                'code' => 'punctual_10',
                'name_th' => 'ตรงต่อเวลา',
                'description' => 'ส่งคืนหนังสือตรงตามกำหนดเวลาครบ 10 ครั้ง',
                'icon' => '⏰',
                'tier' => 'silver',
                'condition_type' => 'on_time',
                'condition_value' => 10,
                'sort_order' => 5,
            ],
            [
                'code' => 'explorer_5',
                'name_th' => 'นักสำรวจหมวดหมู่',
                'description' => 'อ่านหนังสือหลากหลายหมวดหมู่ครบ 5 หมวด',
                'icon' => '🧭',
                'tier' => 'gold',
                'condition_type' => 'category_variety',
                'condition_value' => 5,
                'sort_order' => 6,
            ],
            [
                'code' => 'streak_4',
                'name_th' => 'ไฟแรงต่อเนื่อง',
                'description' => 'ยืมหนังสืออ่านต่อเนื่องกัน 4 สัปดาห์',
                'icon' => '🔥',
                'tier' => 'platinum',
                'condition_type' => 'streak',
                'condition_value' => 4,
                'sort_order' => 7,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::query()->updateOrCreate(
                ['code' => $badge['code']],
                $badge
            );
        }
    }
}
