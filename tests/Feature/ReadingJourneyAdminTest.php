<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\User;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingJourneyAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reading_journey_index(): void
    {
        $this->seed(BadgeSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.journey.index'));

        $response->assertOk();
        $response->assertSee('สถิตินักอ่าน &amp; จัดการเหรียญรางวัล', false);
        $response->assertSee($user->name);
    }

    public function test_admin_can_view_user_reading_journey_detail(): void
    {
        $this->seed(BadgeSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.journey.show', $user));

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee('ปรับปรุงแต้มพิเศษ');
    }

    public function test_admin_can_award_and_revoke_badge_manually(): void
    {
        $this->seed(BadgeSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $badge = Badge::first();

        // Award badge
        $response = $this->actingAs($admin)->post(route('admin.journey.award-badge', $user), [
            'badge_id' => $badge->id,
        ]);
        $response->assertRedirect();
        $this->assertTrue($user->badges()->where('badge_id', $badge->id)->exists());

        // Revoke badge
        $response = $this->actingAs($admin)->delete(route('admin.journey.revoke-badge', [$user, $badge]));
        $response->assertRedirect();
        $this->assertFalse($user->badges()->where('badge_id', $badge->id)->exists());
    }

    public function test_admin_can_adjust_points_manually(): void
    {
        $this->seed(BadgeSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->post(route('admin.journey.adjust-points', $user), [
            'points' => 50,
            'reason' => 'ร่วมกิจกรรมพิเศษ',
        ]);

        $response->assertRedirect();
        $this->assertEquals(50, $user->fresh()->readingStat->total_points);
    }

    public function test_admin_can_manage_badges_crud(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create Badge
        $response = $this->actingAs($admin)->post(route('admin.badges.store'), [
            'code' => 'custom_badge',
            'name_th' => 'เหรียญนักประดิษฐ์',
            'description' => 'สำหรับผู้ที่สร้างสรรค์สิ่งใหม่',
            'icon' => '💡',
            'tier' => 'gold',
            'condition_type' => 'manual',
            'condition_value' => 0,
            'sort_order' => 99,
        ]);

        $response->assertRedirect(route('admin.journey.index'));
        $this->assertDatabaseHas('badges', ['code' => 'custom_badge']);

        $badge = Badge::where('code', 'custom_badge')->first();

        // Update Badge
        $response = $this->actingAs($admin)->put(route('admin.badges.update', $badge), [
            'code' => 'custom_badge',
            'name_th' => 'เหรียญนักประดิษฐ์ขั้นสูง',
            'description' => 'สำหรับผู้ที่สร้างสรรค์สิ่งใหม่ขั้นสูง',
            'icon' => '✨',
            'tier' => 'special',
            'condition_type' => 'manual',
            'condition_value' => 0,
            'sort_order' => 100,
        ]);

        $response->assertRedirect(route('admin.journey.index'));
        $this->assertDatabaseHas('badges', ['name_th' => 'เหรียญนักประดิษฐ์ขั้นสูง']);

        // Delete Badge
        $response = $this->actingAs($admin)->delete(route('admin.badges.destroy', $badge));
        $response->assertRedirect(route('admin.journey.index'));
        $this->assertDatabaseMissing('badges', ['code' => 'custom_badge']);
    }
}
