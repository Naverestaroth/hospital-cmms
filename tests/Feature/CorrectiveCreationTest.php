<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Corrective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorrectiveCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_corrective_report_with_tanggal_instal()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('correctives.store'), [
            'repair_date' => '2026-08-15',
            'jam_laporan' => '10:00',
            'jam_visit' => '10:15',
            'response_time' => '≤ 15 Minutes',
            'room' => 'ICU',
            'asset_name' => 'Patient Monitor',
            'brand' => 'Mindray',
            'type' => 'uMEC10',
            'serial_number' => 'SN123456',
            'tanggal_instal' => '2024',
            'distributor' => 'PT Utama',
            'problem' => 'Display flickering',
            'solution' => 'Replaced cable',
            'inspection_result' => 'Fasilitas berfungsi baik',
            'user_name' => 'Nurse Ratna',
        ]);

        $corrective = Corrective::first();

        $this->assertNotNull($corrective);
        $this->assertEquals('2024', $corrective->tanggal_instal);
        $this->assertEquals('Patient Monitor', $corrective->asset_name);
        $response->assertRedirect(route('correctives.show', $corrective));
    }
}
