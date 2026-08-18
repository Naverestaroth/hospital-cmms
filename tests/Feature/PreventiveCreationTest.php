<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Preventive;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreventiveCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_preventive_report_with_schedule_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('preventives.store'), [
            'room' => 'IGD',
            'schedule_date' => '15/08/2026',
            'asset_code' => 'AST-001',
            'asset_name' => 'Defibrillator',
            'brand' => 'Zoll',
            'type' => 'R Series',
            'serial_number' => 'SN998877',
            'procurement_year' => '2024',
            'technician' => 'Susanto',
            'condition' => 'Baik',
        ]);

        $preventive = Preventive::first();

        $this->assertNotNull($preventive);
        $this->assertStringContainsString('2026-08-15', (string) $preventive->schedule_date);
        $this->assertEquals('Defibrillator', $preventive->asset_name);
        $response->assertRedirect(route('preventives.index'));
    }

    public function test_create_preventive_defaults_schedule_date_if_missing()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('preventives.store'), [
            'room' => 'IGD',
            'asset_name' => 'Defibrillator',
        ]);

        $preventive = Preventive::first();

        $this->assertNotNull($preventive);
        $this->assertStringContainsString(date('Y-m-d'), (string) $preventive->schedule_date);
    }

    public function test_create_preventive_form_only_shows_on_duty_technicians()
    {
        $user = User::factory()->create();

        \App\Models\Technician::create(['name' => 'Tech OnDuty', 'duty_status' => 'On Duty']);
        \App\Models\Technician::create(['name' => 'Tech OffDuty', 'duty_status' => 'Off Duty']);

        $response = $this->actingAs($user)->get(route('preventives.create'));

        $response->assertStatus(200);
        $response->assertSee('Tech OnDuty');
        $response->assertDontSee('Tech OffDuty');
    }
}
