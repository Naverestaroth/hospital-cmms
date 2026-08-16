<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorrectiveOnDutyTechnicianTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_corrective_shows_only_on_duty_technicians()
    {
        $user = User::factory()->create();

        Technician::create([
            'name' => 'Technician Active',
            'duty_status' => 'On Duty',
            'status' => 'Aktif',
        ]);

        Technician::create([
            'name' => 'Technician Inactive',
            'duty_status' => 'Off Duty',
            'status' => 'Aktif',
        ]);

        $response = $this->actingAs($user)->get(route('correctives.create'));

        $response->assertStatus(200);
        $response->assertSee('Technician Active');
        $response->assertDontSee('Technician Inactive');
    }
}
