<?php

namespace App\Imports;

use App\Models\Preventive;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class PreventivesExcelImport implements ToCollection
{
    private bool $dryRun;

    public array $previewRows = [];
    public array $errors = [];
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;
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

            $room = $cell(0);
            $date = $cell(1);
            $assetName = $cell(2);
            $brand = $cell(3);
            $type = $cell(4);
            $serialNumber = $cell(5);
            $checklist = $cell(6);
            $goodCondition = $cell(7);
            $problemFound = $cell(8);
            $condition = $cell(9);
            $notes = $cell(10);
            $technician = $cell(11);

            // Skip completely empty rows
            if (empty($room) && empty($date) && empty($assetName) && empty($brand) && empty($type) && empty($serialNumber)) {
                $this->skipped++;
                continue;
            }
            $scheduleDate = $date;

            if (!empty($scheduleDate)) {

                // Excel serial date
                if (is_numeric($scheduleDate)) {
                    $scheduleDate = Date::excelToDateTimeObject($scheduleDate)
                        ->format('Y-m-d');
                }

                // Year only
                elseif (preg_match('/^\d{4}$/', $scheduleDate)) {
                    $scheduleDate .= '-01-01';
                }

                // Already formatted date
                elseif (strtotime($scheduleDate)) {
                    $scheduleDate = date('Y-m-d', strtotime($scheduleDate));
                }
            }

            $rowData = [
                'room' => $room,
                'schedule_date' => $scheduleDate ?? now()->format('d-m-y'),
                'asset_name' => $assetName,
                'brand' => $brand,
                'type' => $type,
                'serial_number' => $serialNumber,
                'checklist' => $checklist,
                'good_condition' => $goodCondition,
                'problem_found' => $problemFound,
                'condition' => $condition,
                'technician' => $technician,
                'status' => 'Scheduled',
                'notes' => $notes,
            ];

            if ($this->dryRun) {
                $rowData['__row_index'] = $i + 2;
                $this->previewRows[] = $rowData;
                continue;
            }

            try {
                Preventive::create($rowData);
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
}
