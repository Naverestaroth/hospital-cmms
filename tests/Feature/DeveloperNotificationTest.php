<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\DataWipeExecutedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DeveloperNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_wipe_sends_notification_only_to_developer()
    {
        Notification::fake();

        $developer = User::factory()->create(['role' => 'developer']);
        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);
        $teknisi = User::factory()->create(['role' => 'teknisi']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($developer)->post('/settings/wipe', [
            'targets' => ['tickets'],
        ]);

        $response->assertRedirect();

        Notification::assertSentTo($developer, DataWipeExecutedNotification::class);
        Notification::assertNotSentTo($kepalaIpsrs, DataWipeExecutedNotification::class);
        Notification::assertNotSentTo($teknisi, DataWipeExecutedNotification::class);
        Notification::assertNotSentTo($regularUser, DataWipeExecutedNotification::class);
    }

    public function test_developer_notification_unread_badge_read_and_direct_link_flow()
    {
        $developer = User::factory()->create(['role' => 'developer']);

        $this->actingAs($developer)->post('/settings/wipe', [
            'targets' => ['tickets'],
        ]);

        $this->assertEquals(1, $developer->unreadNotifications()->count());

        $notification = $developer->unreadNotifications->first();
        $this->assertNotNull($notification);
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($developer)->get("/notifications/{$notification->id}/read");

        $response->assertRedirect(route('settings') . '?tab=admin_tools');
        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertEquals(0, $developer->fresh()->unreadNotifications()->count());
    }
}
