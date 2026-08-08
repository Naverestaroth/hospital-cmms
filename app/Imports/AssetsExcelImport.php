<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AssetsExcelImport implements ToCollection
{
    private bool $dryRun;
    private int $codeSeed;

    public array $previewRows = [];
    public array $errors = [];
    public int $imported = 0;
    public int $skipped = 0;
    public int $updated = 0;
    public int $failed = 0;

    public function __construct(bool $dryRun = true, int $codeSeed = 1)
    {
        $this->dryRun = $dryRun;
        $this->codeSeed = $codeSeed;
    }

    public function collection(Collection $rows)
    {
        // Default column mapping for Format A (Standard)
        $columnMap = [
            'room' => 0,
            'asset_name' => 1,
            'brand' => 2,
            'type' => 3,
            'serial_number' => 4,
            'procurement_year' => 5,
            'condition' => 6,
            'description' => 7,
        ];

        // Format Detection: Dynamically inspect header row (Row 0)
        if ($rows->count() > 0) {
            $headerRow = $rows->first();
            $detectedMap = [];

            foreach ($headerRow as $colIndex => $colValue) {
                if ($colValue === null) {
                    continue;
                }
                $headerStr = strtolower(trim((string) $colValue));

                if (in_array($headerStr, ['room', 'ruang', 'lokasi'])) {
                    $detectedMap['room'] = $colIndex;
                } elseif (in_array($headerStr, ['asset name', 'nama alat', 'nama aset', 'nama barang', 'asset_name', 'asset'])) {
                    $detectedMap['asset_name'] = $colIndex;
                } elseif (in_array($headerStr, ['brand', 'merk', 'merek'])) {
                    $detectedMap['brand'] = $colIndex;
                } elseif (in_array($headerStr, ['type', 'tipe', 'model'])) {
                    $detectedMap['type'] = $colIndex;
                } elseif (in_array($headerStr, ['serial number', 'serial_number', 'sn', 'no seri', 'no. seri', 'nomor seri'])) {
                    $detectedMap['serial_number'] = $colIndex;
                } elseif (in_array($headerStr, ['procurement year', 'tahun pengadaan', 'tahun', 'procurement_year'])) {
                    $detectedMap['procurement_year'] = $colIndex;
                } elseif (in_array($headerStr, ['condition', 'kondisi', 'status', 'kondisi alat'])) {
                    $detectedMap['condition'] = $colIndex;
                } elseif (in_array($headerStr, ['description', 'keterangan', 'catatan', 'deskripsi'])) {
                    $detectedMap['description'] = $colIndex;
                }
            }

            $firstColHeader = strtolower(trim((string) ($headerRow[0] ?? '')));
            if ($firstColHeader === 'timestamp' && empty($detectedMap)) {
                // Format B (Google Form Export with Timestamp)
                $columnMap = [
                    'room' => 1,
                    'asset_name' => 2,
                    'brand' => 3,
                    'type' => 4,
                    'serial_number' => 5,
                    'procurement_year' => 6,
                    'condition' => 7,
                    'description' => 8,
                ];
            } else {
                $columnMap = array_merge($columnMap, $detectedMap);
            }
        }

        $nextCode = $this->codeSeed;

        foreach ($rows as $i => $row) {
            // Skip header row (index 0)
            if ($i === 0) {
                continue;
            }

            $cell = function (?int $index) use ($row) {
                if ($index === null) {
                    return null;
                }
                $v = $row[$index] ?? null;

                if ($v === null) {
                    return null;
                }

                $value = (string) $v;
                return trim($value) === '' ? null : $value;
            };

            $room = $cell($columnMap['room']);
            $assetName = $cell($columnMap['asset_name']);
            $brand = $cell($columnMap['brand']);
            $type = $cell($columnMap['type']);
            $serialNumber = $cell($columnMap['serial_number']);
            $procYear = $cell($columnMap['procurement_year']);
            $condition = $cell($columnMap['condition']);
            $descriptionCol = $cell($columnMap['description'] ?? null);

            // Skip completely empty rows
            $allNull = empty($room) && empty($assetName) && empty($brand) && empty($type) && empty($serialNumber) && empty($condition) && empty($descriptionCol);

            if ($allNull) {
                $this->skipped++;
                continue;
            }

            $errors = [];
            $procurementDate = $this->normalizeProcurementYear($procYear, $errors);

            // Store predefined status if matched; otherwise preserve exact original text as custom "Other" status
            if (!empty($condition)) {
                $predefinedMap = [
                    'berfungsi' => 'Berfungsi',
                    'dalam perbaikan' => 'dalam perbaikan',
                    'rusak' => 'Rusak',
                    'tidak berfungsi' => 'Rusak',
                    'proses penghapusan' => 'Proses Penghapusan',
                ];
                $lowerCond = strtolower(trim($condition));
                $status = $predefinedMap[$lowerCond] ?? $condition;
            } else {
                $status = 'Berfungsi';
            }

            // Description comes ONLY from the Description/Keterangan column (or NULL if empty)
            $description = !empty($descriptionCol) ? $descriptionCol : null;

            $rowData = [
                'asset_code' => null, // generated later for new records
                'asset_name' => empty($assetName) ? null : $assetName,
                'brand' => empty($brand) ? null : $brand,
                'type' => empty($type) ? null : $type,
                'serial_number' => empty($serialNumber) ? null : $serialNumber,
                'room' => empty($room) ? null : $room,
                'procurement_year' => $procurementDate,
                'status' => $status,
                'description' => $description,
            ];

            // Existing asset check: Update if serial_number exists
            if (!empty($serialNumber)) {
                $existingAsset = Asset::where('serial_number', $serialNumber)->first();
                if ($existingAsset) {
                    if ($this->dryRun) {
                        $rowData['asset_code'] = $existingAsset->asset_code;
                        $rowData['__row_index'] = $i + 2;
                        $rowData['__is_update'] = true;
                        $this->previewRows[] = $rowData;
                        $this->updated++;
                        continue;
                    }

                    try {
                        $updateData = [
                            'asset_name' => empty($assetName) ? $existingAsset->asset_name : $assetName,
                            'brand' => empty($brand) ? $existingAsset->brand : $brand,
                            'type' => empty($type) ? $existingAsset->type : $type,
                            'room' => empty($room) ? $existingAsset->room : $room,
                            'procurement_year' => $procurementDate ?? $existingAsset->procurement_year,
                            'status' => $status,
                            'description' => $description,
                        ];

                        $existingAsset->update($updateData);
                        $this->updated++;
                    } catch (\Throwable $e) {
                        $this->failed++;
                        $this->errors[] = [
                            'row' => $i + 2,
                            'serial_number' => $serialNumber,
                            'errors' => [$e->getMessage()],
                        ];
                    }
                    continue;
                }
            }

            // New Asset Creation
            $rowData['asset_code'] = $this->generateAssetCode($nextCode);
            $nextCode++;

            if ($this->dryRun) {
                $rowData['__row_index'] = $i + 2;
                $rowData['__is_update'] = false;
                $this->previewRows[] = $rowData;
                continue;
            }

            try {
                Asset::create($rowData);
                $this->imported++;
            } catch (\Throwable $e) {
                $this->failed++;
                $this->errors[] = [
                    'row' => $i + 2,
                    'serial_number' => $serialNumber,
                    'errors' => [$e->getMessage()],
                ];
            }
        }
    }

    private function normalizeProcurementYear($procYear, array &$errors): ?string
    {
        $procYear = empty($procYear) ? null : trim((string) $procYear);

        if (empty($procYear)) {
            return null;
        }

        if (preg_match('/^\d{4}$/', $procYear)) {
            return ((int) $procYear) . '-01-01';
        }

        $parsed = null;
        if (preg_match('/^(\d{4})[-.\/](\d{1,2})[-.\/](\d{1,2})$/', $procYear, $matches)) {
            $parsed = $matches[1] . '-01-01';
        } elseif (preg_match('/^(\d{1,2})[\/.-](\d{1,2})[\/.-](\d{4})$/', $procYear, $matches)) {
            $parsed = $matches[3] . '-01-01';
        }

        if ($parsed !== null) {
            return $parsed;
        }

        return null;
    }

    private function generateAssetCode(int $index): string
    {
        return 'AST-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT);
    }
}
