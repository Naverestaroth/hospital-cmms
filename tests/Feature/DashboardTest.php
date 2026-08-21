<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Technician;
use App\Models\Preventive;
use App\Models\Corrective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_technician_workload_widget_combines_ticket_pm_corrective_and_duty_styling()
    {
        $user = User::factory()->create();

        $userTechOn = User::factory()->create(['name' => 'Budi OnDuty', 'role' => 'teknisi']);
        $userTechOff = User::factory()->create(['name' => 'Sari OffDuty', 'role' => 'teknisi']);

        $techOn = Technician::where('user_id', $userTechOn->id)->first();
        $techOn->update(['duty_status' => 'On Duty']);

        $techOff = Technician::where('user_id', $userTechOff->id)->first();
        $techOff->update(['duty_status' => 'Off Duty']);


        Preventive::create([
            'room' => 'IGD',
            'schedule_date' => '2026-08-15',
            'asset_name' => 'EKG Machine',
            'technician' => 'Budi OnDuty',
        ]);

        Corrective::create([
            'room' => 'Poliklinik',
            'asset_name' => 'USG Scanner',
            'repair_date' => '2026-08-15',
            'technician' => 'Sari OffDuty',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Technician Workload');
        $response->assertSee('Budi OnDuty');
        $response->assertSee('Sari OffDuty');
        $response->assertSee('text-[#0B1E26] font-bold');
        $response->assertSee('text-slate-400 font-medium');
    }
}
