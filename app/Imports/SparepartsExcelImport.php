<?php

namespace App\Imports;

use App\Models\Sparepart;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SparepartsExcelImport implements ToCollection
{
    private bool $dryRun;
    private int $codeSeed;

    public array $previewRows = [];
    public array $errors = [];
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;
    public int $failed = 0;

    public function __construct(bool $dryRun = true, int $codeSeed = 1)
    {
        $this->dryRun = $dryRun;
        $this->codeSeed = $codeSeed;
    }

    public function collection(Collection $rows)
    {
        $nextCode = $this->codeSeed;

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

            $partName = $cell(0);
            $stock = $cell(1);
            $unit = $cell(2);
            $location = $cell(3);

            // Skip completely empty rows
            if (empty($partName) && empty($stock) && empty($unit) && empty($location)) {
                $this->skipped++;
                continue;
            }

            $rowData = [
                'part_code' => null, // generated later
                'part_name' => $partName,
                'stock' => $stock ? (int) $stock : 0,
                'unit' => $unit ?? 'Pcs',
                'location' => $location,
            ];

            // Duplicate check: if Part Name already exists
            if (!empty($partName)) {
                $exists = Sparepart::where('part_name', $partName)->exists();
                if ($exists) {
                    $this->duplicates++;
                    continue;
                }
            }

            $rowData['part_code'] = $this->generatePartCode($nextCode);
            $nextCode++;

            if ($this->dryRun) {
                $rowData['__row_index'] = $i + 2;
                $this->previewRows[] = $rowData;
                continue;
            }

            try {
                Sparepart::create($rowData);
                $this->imported++;
            } catch (\Throwable $e) {
                $this->failed++;
                $this->errors[] = [
                    'row' => $i + 2,
                    'part_name' => $partName,
                    'errors' => [$e->getMessage()],
                ];
            }
        }
    }

    private function generatePartCode(int $index): string
    {
        return 'PRT-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT);
    }
}
