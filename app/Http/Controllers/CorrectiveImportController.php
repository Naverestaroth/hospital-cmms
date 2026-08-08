<?php

namespace App\Http\Controllers;

use App\Imports\CorrectivesExcelImport;
use App\Models\Corrective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CorrectiveImportController extends Controller
{
    public function showUpload()
    {
        return view('correctives.import-upload');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:20480'], // 20MB
        ]);

        $file = $request->file('excel_file');
        $tmpPath = $file->store('correctives_import_tmp', 'local');

        $debug = [];
        try {
            $collections = Excel::toCollection(null, $file);
            $sheetIndex = 0;
            $firstSheet = $collections[$sheetIndex] ?? collect();
            $headingRow = $firstSheet[0] ?? null;
            $parsedColumnKeys = is_array($headingRow) ? array_keys($headingRow) : [];
            $firstDataRow = $firstSheet[1] ?? null;

            $debug = [
                'sheet_names' => ['1'],
                'heading_row_1_values' => $headingRow,
                'parsed_column_keys_heading_row_1' => $parsedColumnKeys,
                'first_data_row_2_values' => $firstDataRow,
            ];
        } catch (\Throwable $e) {
            $debug = ['parse_error' => $e->getMessage()];
        }

        $import = new CorrectivesExcelImport(dryRun: true);
        Excel::import($import, $file);

        Session::put('correctives_import_preview_debug', [
            'previewRows_count' => count($import->previewRows),
            'imported' => $import->imported,
            'skipped' => $import->skipped,
            'updated' => $import->updated,
            'failed' => $import->failed,
            'errors_sample' => array_slice($import->errors, 0, 5),
        ]);

        Session::put('correctives_import_preview', [
            'previewRows' => $import->previewRows,
            'summary' => [
                'imported' => $import->imported,
                'skipped' => $import->skipped,
                'updated' => $import->updated,
                'failed' => $import->failed,
            ],
            'errors' => $import->errors,
            'debug_excel_parse' => $debug,
        ]);

        Session::put('correctives_import_tmp_path', $tmpPath);

        return redirect()
            ->route('correctives.import.preview')
            ->with('success', 'Preview ready. Please confirm to import.');
    }

    public function confirmImport(Request $request)
    {
        $payload = Session::get('correctives_import_preview');
        if (!$payload || empty($payload['previewRows'])) {
            return redirect()
                ->route('correctives.import.upload')
                ->with('error', 'No preview data found. Please upload again.');
        }

        $previewRows = $payload['previewRows'];
        \Illuminate\Support\Facades\Log::info('[DEBUG 1] Session previewRows[0]:', ['row_0' => $previewRows[0] ?? null]);

        $imported = 0;
        $updated = 0;
        $failed = 0;

        foreach ($previewRows as $index => $row) {
            \Illuminate\Support\Facades\Log::info('[DEBUG 2] $row before repairDate:', ['index' => $index, 'row' => $row]);

            $repairDate = $row['repair_date'] ?? null;
            $serialNumber = $row['serial_number'] ?? null;
            $assetName = $row['asset_name'] ?? null;
            $room = $row['room'] ?? null;

            $saveData = [
                'repair_date' => $repairDate ?? now()->format('Y-m-d'),
                'jam_laporan' => $row['jam_laporan'] ?? null,
                'jam_visit' => $row['jam_visit'] ?? null,
                'response_time' => $row['response_time'] ?? null,
                'room' => $room,
                'asset_name' => $assetName,
                'brand' => $row['brand'] ?? null,
                'type' => $row['type'] ?? null,
                'serial_number' => $serialNumber,
                'service_type' => $row['service_type'] ?? [],
                'inspection' => $row['inspection'] ?? [],
                'problem' => $row['problem'] ?? null,
                'solution' => $row['solution'] ?? null,
                'sparepart' => $row['sparepart'] ?? null,
                'quantity' => isset($row['quantity']) ? (int) $row['quantity'] : null,
                'notes' => $row['notes'] ?? null,
                'inspection_result' => $row['inspection_result'] ?? null,
                'technician' => $row['technician'] ?? [],
                'user_name' => $row['user_name'] ?? null,
                'status' => 'Open',
            ];

            \Illuminate\Support\Facades\Log::info('[DEBUG 3] $saveData constructed:', ['index' => $index, 'saveData' => $saveData]);

            // Case-insensitive trimmed query to detect existing record
            $query = Corrective::query();
            if (!empty($serialNumber) && $serialNumber !== '-') {
                $cleanSN = strtolower(trim((string) $serialNumber));
                $query->where(DB::raw('LOWER(TRIM(serial_number))'), $cleanSN)
                      ->whereDate('repair_date', $saveData['repair_date']);
            } else {
                $cleanName = strtolower(trim((string) $assetName));
                $cleanRoom = strtolower(trim((string) $room));
                $query->where(DB::raw('LOWER(TRIM(asset_name))'), $cleanName)
                      ->where(DB::raw('LOWER(TRIM(room))'), $cleanRoom)
                      ->whereDate('repair_date', $saveData['repair_date']);
            }

            $existing = $query->first();

            \Illuminate\Support\Facades\Log::info('[DEBUG 4] Payload immediately before DB save/update:', [
                'index' => $index,
                'action' => $existing ? 'UPDATE' : 'CREATE',
                'existing_id' => $existing?->id,
                'saveData' => $saveData,
            ]);

            try {
                if ($existing) {
                    $existing->update($saveData);
                    $updated++;
                } else {
                    Corrective::create($saveData);
                    $imported++;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('[DEBUG DB ERROR] Save failed:', ['error' => $e->getMessage()]);
                $failed++;
            }
        }

        $tmpPath = Session::get('correctives_import_tmp_path');
        if ($tmpPath) {
            Storage::disk('local')->delete($tmpPath);
        }

        Session::forget('correctives_import_preview');
        Session::forget('correctives_import_preview_debug');
        Session::forget('correctives_import_tmp_path');

        return redirect()
            ->route('correctives.index')
            ->with('success', sprintf(
                'Excel import completed. Imported: %d, Updated: %d, Failed: %d.',
                $imported,
                $updated,
                $failed
            ));
    }

    public function previewPage()
    {
        return view('correctives.import-preview');
    }
}
