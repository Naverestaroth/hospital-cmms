<?php

namespace Tests\Feature;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTechnicianDutyStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_create_only_shows_on_duty_technicians(): void
    {
        $user = User::factory()->create();

        $onDutyTech = Technician::create([
            'name' => 'Budi OnDuty',
            'duty_status' => 'On Duty',
        ]);

        $offDutyTech = Technician::create([
            'name' => 'Siti OffDuty',
            'duty_status' => 'Off Duty',
        ]);

        $response = $this->actingAs($user)->get(route('tickets.create'));

        $response->assertStatus(200);
        $response->assertSee('Budi OnDuty');
        $response->assertDontSee('Siti OffDuty');
    }
}
