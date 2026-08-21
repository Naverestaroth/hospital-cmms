<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewTicketBroadcastNotification;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TechnicianNotificationTest extends TestCase
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

    public function test_new_ticket_broadcasts_only_to_on_duty_technicians_with_user_accounts()
    {
        Notification::fake();

        $userOnDuty = User::factory()->create(['role' => 'teknisi']);
        $techOnDuty = Technician::where('user_id', $userOnDuty->id)->first();
        if (!$techOnDuty) {
            $techOnDuty = Technician::create(['name' => 'Budi OnDuty', 'email' => $userOnDuty->email, 'user_id' => $userOnDuty->id]);
        }
        $techOnDuty->update(['manual_override' => 'On Duty']);

        $userOffDuty = User::factory()->create(['role' => 'teknisi']);
        $techOffDuty = Technician::where('user_id', $userOffDuty->id)->first();
        if (!$techOffDuty) {
            $techOffDuty = Technician::create(['name' => 'Sari OffDuty', 'email' => $userOffDuty->email, 'user_id' => $userOffDuty->id]);
        }
        $techOffDuty->update(['manual_override' => 'Off Duty']);


        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);
        $developer = User::factory()->create(['role' => 'developer']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $asset = $this->createDummyAsset();

        $response = $this->actingAs($regularUser)->post('/tickets', [
            'room'         => 'IGD',
            'asset_id'     => $asset->id,
            'reported_by'  => 'Dr. Test',
            'creator_type' => 'User',
            'issue'        => 'Infusion pump mati total',
            'priority'     => 'High',
        ]);

        $response->assertRedirect('/tickets');

        Notification::assertSentTo($userOnDuty, NewTicketBroadcastNotification::class);
        Notification::assertNotSentTo($userOffDuty, NewTicketBroadcastNotification::class);
        Notification::assertNotSentTo($kepalaIpsrs, NewTicketBroadcastNotification::class);
        Notification::assertNotSentTo($developer, NewTicketBroadcastNotification::class);
        Notification::assertNotSentTo($regularUser, NewTicketBroadcastNotification::class);
    }

    public function test_ticket_assignment_sends_notification_only_to_assigned_technician()
    {
        Notification::fake();

        $userTech1 = User::factory()->create(['role' => 'teknisi']);
        $tech1 = Technician::create([
            'name'            => 'Tech 1',
            'email'           => $userTech1->email,
            'duty_status'     => 'On Duty',
            'manual_override' => 'On Duty',
            'user_id'         => $userTech1->id,
        ]);

        $userTech2 = User::factory()->create(['role' => 'teknisi']);
        $tech2 = Technician::create([
            'name'            => 'Tech 2',
            'email'           => $userTech2->email,
            'duty_status'     => 'On Duty',
            'manual_override' => 'On Duty',
            'user_id'         => $userTech2->id,
        ]);

        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);
        $asset = $this->createDummyAsset();

        $ticket = Ticket::create([
            'ticket_code' => 'TCK-2026-555',
            'asset_id'    => $asset->id,
            'reported_by' => 'User Test',
            'room'        => 'Ruang VIP',
            'issue'       => 'Pending perbaikan',
            'status'      => 'Approved',
            'priority'    => 'Medium',
        ]);

        $response = $this->actingAs($kepalaIpsrs)->post("/tickets/{$ticket->id}/assign", [
            'technician_ids' => [$tech1->id],
        ]);

        $response->assertRedirect();

        Notification::assertSentTo($userTech1, TicketAssignedNotification::class);
        Notification::assertNotSentTo($userTech2, TicketAssignedNotification::class);
    }

    public function test_technician_notification_unread_badge_read_and_direct_link_flow()
    {
        $userTech = User::factory()->create(['role' => 'teknisi']);
        $asset = $this->createDummyAsset();

        $ticket = Ticket::create([
            'ticket_code' => 'TCK-2026-444',
            'asset_id'    => $asset->id,
            'reported_by' => 'User Test',
            'room'        => 'Kamar 101',
            'issue'       => 'Air conditioner tidak dingin',
            'status'      => 'In Progress',
            'priority'    => 'Low',
        ]);

        $userTech->notify(new NewTicketBroadcastNotification($ticket));

        $this->assertEquals(1, $userTech->unreadNotifications()->count());

        $notification = $userTech->unreadNotifications->first();
        $this->assertNotNull($notification);
        $this->assertNull($notification->read_at);

        $response = $this->actingAs($userTech)->get("/notifications/{$notification->id}/read");

        $response->assertRedirect(route('tickets.show', $ticket->id));
        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertEquals(0, $userTech->fresh()->unreadNotifications()->count());
    }
}
