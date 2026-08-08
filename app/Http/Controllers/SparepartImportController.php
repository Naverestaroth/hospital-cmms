<?php

namespace App\Http\Controllers;

use App\Imports\SparepartsExcelImport;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SparepartImportController extends Controller
{
    public function showUpload()
    {
        return view('spareparts.import-upload');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:20480'], // 20MB
        ]);

        $file = $request->file('excel_file');
        $tmpPath = $file->store('spareparts_import_tmp', 'local');

        $existingMax = (int) Sparepart::query()
            ->where('part_code', 'LIKE', 'PRT-%')
            ->selectRaw('MAX(CAST(SUBSTRING(part_code, 5) AS UNSIGNED)) as m')
            ->value('m');

        $existingMax = $existingMax > 0 ? $existingMax : 0;
        $codeSeed = $existingMax + 1;

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

        $import = new SparepartsExcelImport(dryRun: true, codeSeed: $codeSeed);
        Excel::import($import, $file);

        Session::put('spareparts_import_preview_debug', [
            'previewRows_count' => count($import->previewRows),
            'imported' => $import->imported,
            'skipped' => $import->skipped,
            'duplicates' => $import->duplicates,
            'failed' => $import->failed,
            'errors_sample' => array_slice($import->errors, 0, 5),
        ]);

        Session::put('spareparts_import_preview', [
            'previewRows' => $import->previewRows,
            'summary' => [
                'imported' => $import->imported,
                'skipped' => $import->skipped,
                'duplicates' => $import->duplicates,
                'failed' => $import->failed,
            ],
            'errors' => $import->errors,
            'debug_excel_parse' => $debug,
        ]);

        Session::put('spareparts_import_tmp_path', $tmpPath);

        return redirect()
            ->route('spareparts.import.preview')
            ->with('success', 'Preview ready. Please confirm to import.');
    }

    public function confirmImport(Request $request)
    {
        $payload = Session::get('spareparts_import_preview');
        if (!$payload || empty($payload['previewRows'])) {
            return redirect()
                ->route('spareparts.import.upload')
                ->with('error', 'No preview data found. Please upload again.');
        }

        $tmpPath = Session::get('spareparts_import_tmp_path');
        if (!$tmpPath) {
            return redirect()
                ->route('spareparts.import.upload')
                ->with('error', 'Temporary upload expired. Please upload again.');
        }

        $existingMax = (int) Sparepart::query()
            ->where('part_code', 'LIKE', 'PRT-%')
            ->selectRaw('MAX(CAST(SUBSTRING(part_code, 5) AS UNSIGNED)) as m')
            ->value('m');

        $existingMax = $existingMax > 0 ? $existingMax : 0;
        $codeSeed = $existingMax + 1;

        $import = new SparepartsExcelImport(dryRun: false, codeSeed: $codeSeed);
        Excel::import($import, Storage::disk('local')->path($tmpPath));

        Session::forget('spareparts_import_preview');
        Session::forget('spareparts_import_tmp_path');
        Storage::disk('local')->delete($tmpPath);

        return redirect()
            ->route('spareparts.index')
            ->with('success', sprintf(
                'Excel import completed. Imported: %d, Skipped: %d, Duplicate: %d, Failed: %d.',
                $import->imported,
                $import->skipped,
                $import->duplicates,
                $import->failed
            ));
    }

    public function previewPage()
    {
        return view('spareparts.import-preview');
    }
}
