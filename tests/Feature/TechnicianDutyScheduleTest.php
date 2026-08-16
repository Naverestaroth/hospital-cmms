<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Technician;
use App\Models\TechnicianSchedule;
use App\Models\TechnicianScheduleException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechnicianDutyScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_duty_matches_imported_schedule()
    {
        $tech = Technician::create([
            'name' => 'Technician Shift Test',
            'duty_status' => 'Off Duty',
        ]);

        $now = Carbon::now();

        // Create shift for today covering current time
        TechnicianSchedule::create([
            'technician_id' => $tech->id,
            'shift_name'    => 'Shift Pagi',
            'shift_date'    => $now->toDateString(),
            'start_time'    => '00:00:00',
            'end_time'      => '23:59:59',
        ]);

        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
        $this->assertTrue(Technician::onDuty()->pluck('id')->contains($tech->id));
    }

    public function test_manual_override_has_top_priority()
    {
        $tech = Technician::create([
            'name' => 'Technician Override Test',
            'duty_status' => 'Off Duty',
            'manual_override' => 'Off Duty',
        ]);

        $now = Carbon::now();

        // Shift active
        TechnicianSchedule::create([
            'technician_id' => $tech->id,
            'shift_name'    => 'Shift Pagi',
            'shift_date'    => $now->toDateString(),
            'start_time'    => '00:00:00',
            'end_time'      => '23:59:59',
        ]);

        // Manual override set to Off Duty -> Must remain Off Duty!
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        // Force manual override to On Duty -> Must switch to On Duty!
        $tech->update(['manual_override' => 'On Duty']);
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
    }

    public function test_overtime_exception_triggers_on_duty_outside_shift()
    {
        $tech = Technician::create([
            'name' => 'Technician Overtime Test',
            'duty_status' => 'Off Duty',
        ]);

        $now = Carbon::now();

        TechnicianScheduleException::create([
            'technician_id'   => $tech->id,
            'type'            => 'Lembur',
            'override_status' => 'On Duty',
            'start_at'        => $now->copy()->subHour(),
            'end_at'          => $now->copy()->addHour(),
            'notes'           => 'Emergency overtime',
        ]);

        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Lembur (On Duty)', $tech->fresh()->duty_source_label);
    }

    public function test_leave_exception_triggers_off_duty_during_shift()
    {
        $tech = Technician::create([
            'name' => 'Technician Leave Test',
            'duty_status' => 'On Duty',
        ]);

        $now = Carbon::now();

        TechnicianSchedule::create([
            'technician_id' => $tech->id,
            'shift_name'    => 'Shift Pagi',
            'shift_date'    => $now->toDateString(),
            'start_time'    => '00:00:00',
            'end_time'      => '23:59:59',
        ]);

        TechnicianScheduleException::create([
            'technician_id'   => $tech->id,
            'type'            => 'Izin',
            'override_status' => 'Off Duty',
            'start_at'        => $now->copy()->subHour(),
            'end_at'          => $now->copy()->addHour(),
            'notes'           => 'Izin sakit',
        ]);

        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Izin (Off Duty)', $tech->fresh()->duty_source_label);
    }

    public function test_status_returns_automatically_after_exception_end_date()
    {
        $now = Carbon::now();

        // 1. After Lembur ends -> status returns to Edit status (Off Duty)
        $techLembur = Technician::create([
            'name' => 'Tech Overtime Ended',
            'duty_status' => 'Off Duty',
        ]);
        TechnicianScheduleException::create([
            'technician_id'   => $techLembur->id,
            'type'            => 'Lembur',
            'override_status' => 'On Duty',
            'start_at'        => $now->copy()->subHours(5),
            'end_at'          => $now->copy()->subHour(),
        ]);
        $this->assertEquals('Off Duty', $techLembur->fresh()->duty_status);
        $this->assertEquals('Edit Technician (Off Duty)', $techLembur->fresh()->duty_source_label);

        // 2. After Cuti/Sakit ends -> status returns to Edit status (On Duty)
        $techLeave = Technician::create([
            'name' => 'Tech Leave Ended',
            'duty_status' => 'On Duty',
        ]);
        TechnicianScheduleException::create([
            'technician_id'   => $techLeave->id,
            'type'            => 'Cuti',
            'override_status' => 'Off Duty',
            'start_at'        => $now->copy()->subDays(2),
            'end_at'          => $now->copy()->subHour(),
        ]);
        $this->assertEquals('On Duty', $techLeave->fresh()->duty_status);
        $this->assertEquals('Edit Technician (On Duty)', $techLeave->fresh()->duty_source_label);
    }

    public function test_exact_minute_time_evaluation_for_exceptions()
    {
        $tech = Technician::create([
            'name' => 'Exact Minute Test Tech',
            'duty_status' => 'Off Duty',
        ]);

        $startTime = Carbon::create(2026, 8, 15, 14, 0, 0);
        $endTime   = Carbon::create(2026, 8, 15, 18, 0, 0);

        TechnicianScheduleException::create([
            'technician_id'   => $tech->id,
            'type'            => 'Lembur',
            'override_status' => 'On Duty',
            'start_at'        => $startTime,
            'end_at'          => $endTime,
        ]);

        // 1. Before 14:00 -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 15, 13, 59, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        // 2. During 14:00 - 18:00 -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 15, 14, 0, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow(Carbon::create(2026, 8, 15, 16, 30, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        // 3. Right after 18:00 -> returns to Off Duty immediately
        Carbon::setTestNow(Carbon::create(2026, 8, 15, 18, 1, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow();
    }

    public function test_cross_midnight_exception_evaluation()
    {
        $tech = Technician::create([
            'name' => 'Cross Midnight Tech',
            'duty_status' => 'Off Duty',
        ]);

        TechnicianScheduleException::create([
            'technician_id'   => $tech->id,
            'type'            => 'Lembur',
            'override_status' => 'On Duty',
            'start_at'        => Carbon::create(2026, 8, 15, 22, 0, 0),
            'end_at'          => Carbon::create(2026, 8, 16, 4, 0, 0),
        ]);

        // 1. Before Start (Aug 15 21:59) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 15, 21, 59, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        // 2. Before Midnight (Aug 15 23:00) -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 15, 23, 0, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        // 3. After Midnight (Aug 16 02:00) -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 2, 0, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        // 4. Right after End (Aug 16 04:01) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 4, 1, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow();
    }

    public function test_short_duration_1_to_2_minute_exception_evaluation()
    {
        $tech = Technician::create([
            'name' => 'Short Duration Tech',
            'duty_status' => 'Off Duty',
        ]);

        // 2-minute overtime exception: 10:00:00 to 10:02:00
        TechnicianScheduleException::create([
            'technician_id'   => $tech->id,
            'type'            => 'Lembur',
            'override_status' => 'On Duty',
            'start_at'        => Carbon::create(2026, 8, 16, 10, 0, 0),
            'end_at'          => Carbon::create(2026, 8, 16, 10, 2, 0),
        ]);

        // Before start (09:59:59) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 9, 59, 59));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Edit Technician (Off Duty)', $tech->fresh()->duty_source_label);

        // Minute 1 (10:00:30) -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 10, 0, 30));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Lembur (On Duty)', $tech->fresh()->duty_source_label);

        // Minute 2 (10:01:45) -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 10, 1, 45));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Lembur (On Duty)', $tech->fresh()->duty_source_label);

        // Right after Minute 2 (10:02:00 / 10:02:01) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 10, 2, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Edit Technician (Off Duty)', $tech->fresh()->duty_source_label);

        Carbon::setTestNow();
    }

    public function test_technician_duty_statuses_polling_endpoint()
    {
        $user = User::factory()->create();
        $tech = Technician::create([
            'name' => 'Polling Test Tech',
            'duty_status' => 'Off Duty',
        ]);

        $response = $this->actingAs($user)->get(route('technicians.duty-statuses'));

        $response->assertOk();
        $response->assertJsonStructure([
            'statuses',
            'onDutyCount',
            'offDutyCount',
            'totalCount',
        ]);
        $response->assertJsonFragment([
            'duty_status' => 'Off Duty',
            'duty_source_label' => 'Edit Technician (Off Duty)',
        ]);
    }

    public function test_pdf_schedule_auto_duty_scenario_lifecycle()
    {
        // Initial condition = Off Duty
        $tech = Technician::create([
            'name' => 'Auto Duty Scenario Tech',
            'duty_status' => 'Off Duty',
        ]);

        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        // Schedule entry for 2026-08-25 (08:00 - 16:00, Shift Pagi)
        TechnicianSchedule::create([
            'technician_id' => $tech->id,
            'shift_date'    => '2026-08-25',
            'start_time'    => '08:00:00',
            'end_time'      => '16:00:00',
            'shift_name'    => 'Shift Pagi',
        ]);

        // 1. Before shift (07:59:59) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 25, 7, 59, 59));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        // 2. During shift (10:30:00) -> Directly On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 25, 10, 30, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Shift Pagi (On Duty)', $tech->fresh()->duty_source_label);

        // 3. Exactly after shift ends (16:00:00) -> Automatically Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 25, 16, 0, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Jadwal Off (Off Duty)', $tech->fresh()->duty_source_label);

        Carbon::setTestNow();
    }

    public function test_auto_vs_manual_mode_and_exception_reversion()
    {
        $tech = Technician::create([
            'name' => 'Separation Mode Tech',
            'duty_status' => 'Off Duty',
            'manual_override' => null, // Auto Duty
        ]);

        TechnicianSchedule::create([
            'technician_id' => $tech->id,
            'shift_name'    => 'Shift Siang',
            'shift_date'    => '2026-08-30',
            'start_time'    => '14:00:00',
            'end_time'      => '22:00:00',
        ]);

        // 1. Auto Duty during shift (15:00:00) -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 30, 15, 0, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        // 2. Exception active (Izin 14:30 - 16:30) -> Off Duty
        $exc = TechnicianScheduleException::create([
            'technician_id'   => $tech->id,
            'type'            => 'Izin',
            'override_status' => 'Off Duty',
            'start_at'        => Carbon::create(2026, 8, 30, 14, 30, 0),
            'end_at'          => Carbon::create(2026, 8, 30, 16, 30, 0),
        ]);

        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Izin (Off Duty)', $tech->fresh()->duty_source_label);

        // 3. Exception finished (17:00:00) -> Reverts back to Auto Duty (On Duty)
        Carbon::setTestNow(Carbon::create(2026, 8, 30, 17, 0, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Shift Siang (On Duty)', $tech->fresh()->duty_source_label);

        // 4. Switch to Manual Duty (manual_override = Off Duty) -> Forces Off Duty regardless of schedule
        $tech->update(['manual_override' => 'Off Duty']);
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);
        $this->assertEquals('Edit Technician (Off Duty)', $tech->fresh()->duty_source_label);

        Carbon::setTestNow();
    }

    public function test_import_schedule_via_excel_file()
    {
        $user = User::factory()->create();
        $tech = Technician::create([
            'name' => 'Susanto',
            'duty_status' => 'Off Duty',
        ]);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'JADWAL TEKNISI AGUSTUS 2026');
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NAMA');
        $sheet->setCellValue('C3', '25');
        $sheet->setCellValue('D3', '26');
        $sheet->setCellValue('E3', '27');

        $sheet->setCellValue('A4', '1');
        $sheet->setCellValue('B4', 'Susanto');
        $sheet->setCellValue('C4', 'P');
        $sheet->setCellValue('D4', 'S');
        $sheet->setCellValue('E4', 'L');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'excel_test') . '.xlsx';
        $writer->save($tempPath);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempPath,
            'schedule.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($user)->post(route('technicians.import-schedule'), [
            'schedule_file' => $uploadedFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('technician_schedules', [
            'technician_id' => $tech->id,
            'shift_name'    => 'Shift Pagi',
            'start_time'    => '08:00:00',
            'end_time'      => '16:00:00',
        ]);

        // Verify Auto Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 25, 10, 0, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow();
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }

    public function test_import_schedule_via_hybrid_excel_file()
    {
        $user = User::factory()->create();
        $tech1 = Technician::create([
            'name' => 'Susanto',
            'duty_status' => 'Off Duty',
        ]);
        $tech2 = Technician::create([
            'name' => 'Ghazali',
            'duty_status' => 'Off Duty',
        ]);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Matrix Part
        $sheet->setCellValue('A1', 'JADWAL TEKNISI AGUSTUS 2026');
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NAMA');
        $sheet->setCellValue('C3', '25');
        $sheet->setCellValue('D3', '26');
        $sheet->setCellValue('E3', '27');

        $sheet->setCellValue('A4', '1');
        $sheet->setCellValue('B4', 'Susanto');
        $sheet->setCellValue('C4', 'P');

        // 2. Detail Part (row-by-row format in same sheet)
        $sheet->setCellValue('A7', 'Ghazali');
        $sheet->setCellValue('B7', '2026-08-26');
        $sheet->setCellValue('C7', '14:00');
        $sheet->setCellValue('D7', '22:00');
        $sheet->setCellValue('E7', 'Shift Siang');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'hybrid_excel_test') . '.xlsx';
        $writer->save($tempPath);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempPath,
            'hybrid_schedule.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($user)->post(route('technicians.import-schedule'), [
            'schedule_file' => $uploadedFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify Matrix entry for Susanto
        $this->assertDatabaseHas('technician_schedules', [
            'technician_id' => $tech1->id,
            'shift_name'    => 'Shift Pagi',
            'start_time'    => '08:00:00',
            'end_time'      => '16:00:00',
        ]);

        // Verify Detail entry for Ghazali
        $this->assertDatabaseHas('technician_schedules', [
            'technician_id' => $tech2->id,
            'shift_name'    => 'Shift Siang',
            'start_time'    => '14:00:00',
            'end_time'      => '22:00:00',
        ]);

        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }

    public function test_auto_duty_status_transition_before_during_after_shift()
    {
        $tech = Technician::create([
            'name' => 'Syiefa Test',
            'duty_status' => 'Off Duty',
        ]);

        TechnicianSchedule::create([
            'technician_id' => $tech->id,
            'shift_date'    => '2026-08-16',
            'shift_name'    => 'TEST',
            'start_time'    => '18:20:00',
            'end_time'      => '18:25:00',
        ]);

        // 1. Sebelum start_time (18:19) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 18, 19, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        // 2. Saat rentang waktu (18:20 s/d 18:25) -> On Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 18, 20, 0));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow(Carbon::create(2026, 8, 16, 18, 24, 59));
        $this->assertEquals('On Duty', $tech->fresh()->duty_status);

        // 3. Setelah end_time (18:25 dan seterusnya) -> Off Duty
        Carbon::setTestNow(Carbon::create(2026, 8, 16, 18, 25, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow(Carbon::create(2026, 8, 16, 18, 30, 0));
        $this->assertEquals('Off Duty', $tech->fresh()->duty_status);

        Carbon::setTestNow();
    }
}
