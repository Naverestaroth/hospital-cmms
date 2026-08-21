<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketCompletedNotification;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class KepalaIpsrsNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function createDummyAsset(): Asset
    {
        return Asset::create([
            'asset_code' => 'AST-TEST-' . rand(100, 999),
            'asset_name' => 'Equipment Test',
            'room'       => 'IGD',
            'status'     => 'berfungsi',
        ]);
    }

    public function test_ticket_created_notification_is_sent_only_to_kepala_ipsrs()
    {
        Notification::fake();

        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);
        $teknisi = User::factory()->create(['role' => 'teknisi']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $asset = $this->createDummyAsset();

        $response = $this->actingAs($regularUser)->post('/tickets', [
            'room'          => 'IGD',
            'asset_id'      => $asset->id,
            'reported_by'   => 'Dr. Test',
            'creator_type'  => 'User',
            'issue'         => 'Monitor tidak bisa menyala',
            'priority'      => 'High',
        ]);

        $response->assertRedirect('/tickets');

        Notification::assertSentTo($kepalaIpsrs, TicketCreatedNotification::class);
        Notification::assertNotSentTo($teknisi, TicketCreatedNotification::class);
        Notification::assertNotSentTo($regularUser, TicketCreatedNotification::class);
    }

    public function test_ticket_completed_notification_is_sent_only_to_kepala_ipsrs()
    {
        Notification::fake();

        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);
        $teknisi = User::factory()->create(['role' => 'teknisi']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $asset = $this->createDummyAsset();

        $ticket = Ticket::create([
            'ticket_code' => 'TCK-2026-999',
            'asset_id'    => $asset->id,
            'reported_by' => 'User Test',
            'room'        => 'ICU',
            'issue'       => 'Kerusakan alat',
            'status'      => 'In Progress',
            'priority'    => 'Medium',
        ]);

        $response = $this->actingAs($teknisi)->post("/tickets/{$ticket->id}/status", [
            'status' => 'Repair Completed',
            'notes'  => 'Perbaikan kompresor selesai',
        ]);


        $response->assertRedirect();

        Notification::assertSentTo($kepalaIpsrs, TicketCompletedNotification::class);
        Notification::assertNotSentTo($teknisi, TicketCompletedNotification::class);
        Notification::assertNotSentTo($regularUser, TicketCompletedNotification::class);
    }

    public function test_notification_read_and_redirect_flow()
    {
        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);

        $asset = $this->createDummyAsset();

        $ticket = Ticket::create([
            'ticket_code' => 'TCK-2026-888',
            'asset_id'    => $asset->id,
            'reported_by' => 'User Test',
            'room'        => 'VIP 1',
            'issue'       => 'Kerusakan alat',
            'status'      => 'Waiting Approval',
            'priority'    => 'High',
        ]);

        $kepalaIpsrs->notify(new TicketCreatedNotification($ticket));

        $notification = $kepalaIpsrs->unreadNotifications->first();
        $this->assertNotNull($notification);
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($kepalaIpsrs)->get("/notifications/{$notification->id}/read");

        $response->assertRedirect(route('tickets.show', $ticket->id));
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_mark_all_notifications_as_read()
    {
        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);

        $asset = $this->createDummyAsset();

        $ticket = Ticket::create([
            'ticket_code' => 'TCK-2026-777',
            'asset_id'    => $asset->id,
            'reported_by' => 'User Test',
            'room'        => 'Radiologi',
            'issue'       => 'Kerusakan alat',
            'status'      => 'Waiting Approval',
            'priority'    => 'Medium',
        ]);

        $kepalaIpsrs->notify(new TicketCreatedNotification($ticket));
        $kepalaIpsrs->notify(new TicketCompletedNotification($ticket));

        $this->assertEquals(2, $kepalaIpsrs->unreadNotifications()->count());

        $response = $this->actingAs($kepalaIpsrs)->post('/notifications/mark-all-read');

        $response->assertRedirect();
        $this->assertEquals(0, $kepalaIpsrs->fresh()->unreadNotifications()->count());
    }
}
