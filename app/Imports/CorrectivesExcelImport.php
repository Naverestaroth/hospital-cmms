<?php

namespace App\Imports;

use App\Models\Corrective;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CorrectivesExcelImport implements ToCollection
{
    private bool $dryRun;

    public array $previewRows = [];
    public array $errors = [];
    public int $imported = 0;
    public int $skipped = 0;
    public int $updated = 0;
    public int $failed = 0;

    public function __construct(bool $dryRun = true)
    {
        $this->dryRun = $dryRun;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            // Skip header row
            if ($i === 0) {
                continue;
            }

            $cell = function (int $index) use ($row) {
                $v = $row[$index] ?? null;
                if ($v === null) {
                    return null;
                }
                $value = (string) $v;
                return trim($value) === '' ? null : $value;
            };

            $repairDate = $cell(0);
            $jamLaporanStr = $cell(1);
            $jamVisitStr = $cell(2);
            $responseTime = $cell(3);
            $serviceTypeStr = $cell(4);
            $room = $cell(5);
            $assetName = $cell(6);
            $brand = $cell(7);
            $type = $cell(8);
            $serialNumber = $cell(9);
            $tanggalInstal = $cell(10); // skip saving
            $distributor = $cell(11); // skip saving
            $inspectionStr = $cell(12);
            $problem = $cell(13);
            $solution = $cell(14);
            $sparepart = $cell(15);
            $quantity = $cell(16);
            $notes = $cell(17); // Keterangan
            $result = $cell(18); // Hasil Pemeriksaan -> inspection_result
            $technicianStr = $cell(19);
            $userName = $cell(20);

            // Skip completely empty rows
            if (empty($repairDate) && empty($room) && empty($assetName) && empty($brand) && empty($type) && empty($serialNumber)) {
                $this->skipped++;
                continue;
            }

            $dateVal = $this->parseDate($repairDate);
            $jamLaporan = $this->parseTime($jamLaporanStr);
            $jamVisit = $this->parseTime($jamVisitStr);

            $serviceType = $serviceTypeStr ? array_map('trim', explode(',', $serviceTypeStr)) : [];
            $inspection = $inspectionStr ? array_map('trim', explode(',', $inspectionStr)) : [];
            $technicians = $technicianStr ? array_map('trim', explode(',', $technicianStr)) : [];

            $rowData = [
                'repair_date' => $dateVal ?? now()->format('Y-m-d'),
                'jam_laporan' => $jamLaporan,
                'jam_visit' => $jamVisit,
                'response_time' => $responseTime,
                'room' => $room,
                'asset_name' => $assetName,
                'brand' => $brand,
                'type' => $type,
                'serial_number' => $serialNumber,
                'service_type' => $serviceType,
                'inspection' => $inspection,
                'problem' => $problem,
                'solution' => $solution,
                'sparepart' => $sparepart,
                'quantity' => $quantity ? (int) $quantity : null,
                'notes' => $notes,
                'inspection_result' => $result,
                'technician' => $technicians,
                'user_name' => $userName,
                'status' => 'Open',
            ];

            // Flexible case-insensitive trimmed query to detect existing records
            $query = Corrective::query();
            $effectiveDate = $rowData['repair_date'];

            if (!empty($serialNumber) && $serialNumber !== '-') {
                $cleanSN = strtolower(trim($serialNumber));
                $query->where(DB::raw('LOWER(TRIM(serial_number))'), $cleanSN)
                      ->whereDate('repair_date', $effectiveDate);
            } else {
                $cleanName = strtolower(trim((string) $assetName));
                $cleanRoom = strtolower(trim((string) $room));
                $query->where(DB::raw('LOWER(TRIM(asset_name))'), $cleanName)
                      ->where(DB::raw('LOWER(TRIM(room))'), $cleanRoom)
                      ->whereDate('repair_date', $effectiveDate);
            }

            $existingCorrective = $query->first();

            if ($existingCorrective) {
                if ($this->dryRun) {
                    $rowData['__row_index'] = $i + 2;
                    $rowData['__is_update'] = true;
                    $this->previewRows[] = $rowData;
                    $this->updated++;
                    continue;
                }

                try {
                    $existingCorrective->update([
                        'repair_date' => $rowData['repair_date'],
                        'jam_laporan' => $jamLaporan,
                        'jam_visit' => $jamVisit,
                        'response_time' => $responseTime,
                        'room' => $room,
                        'asset_name' => $assetName,
                        'brand' => $brand,
                        'type' => $type,
                        'serial_number' => $serialNumber,
                        'service_type' => $serviceType,
                        'inspection' => $inspection,
                        'problem' => $problem,
                        'solution' => $solution,
                        'sparepart' => $sparepart,
                        'quantity' => $quantity ? (int) $quantity : null,
                        'notes' => $notes,
                        'inspection_result' => $result,
                        'technician' => $technicians,
                        'user_name' => $userName,
                    ]);
                    $this->updated++;
                } catch (\Throwable $e) {
                    $this->failed++;
                    $this->errors[] = [
                        'row' => $i + 2,
                        'asset_name' => $assetName,
                        'errors' => [$e->getMessage()],
                    ];
                }
                continue;
            }

            if ($this->dryRun) {
                $rowData['__row_index'] = $i + 2;
                $rowData['__is_update'] = false;
                $this->previewRows[] = $rowData;
                continue;
            }

            try {
                Corrective::create($rowData);
                $this->imported++;
            } catch (\Throwable $e) {
                $this->failed++;
                $this->errors[] = [
                    'row' => $i + 2,
                    'asset_name' => $assetName,
                    'errors' => [$e->getMessage()],
                ];
            }
        }
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $raw = trim((string) $value);

        // 1. Convert Excel numeric serial dates (e.g. 46034)
        if (is_numeric($raw) && (float) $raw > 1000 && (float) $raw < 100000) {
            try {
                $dateTime = ExcelDate::excelToDateTimeObject((float) $raw);
                return $dateTime->format('Y-m-d');
            } catch (\Throwable $e) {
                // fallback
            }
        }

        // 2. Normalize 4-digit years (e.g. "2026")
        if (preg_match('/^\d{4}$/', $raw)) {
            return $raw . '-01-01';
        }

        // 3. Parse formatted date strings (e.g. "2026-07-26", "26/07/2026", "26-07-2026")
        if (preg_match('/^(\d{4})[-.\/](\d{1,2})[-.\/](\d{1,2})$/', $raw, $m)) {
            return sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
        }
        if (preg_match('/^(\d{1,2})[-.\/](\d{1,2})[-.\/](\d{4})$/', $raw, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }

        return $raw;
    }

    private function parseTime($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $raw = trim((string) $value);

        // 1. Convert Excel fraction of a day (e.g. 0.35416666666667 or numeric < 1)
        if (is_numeric($raw) && (float) $raw >= 0 && (float) $raw < 1) {
            $totalSeconds = (int) round((float) $raw * 86400);
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        // 2. Parse formatted time string (e.g. "08:30", "08:30:00", "8:30 AM")
        try {
            $timestamp = strtotime($raw);
            if ($timestamp !== false) {
                return date('H:i:s', $timestamp);
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return $raw;
    }
}
