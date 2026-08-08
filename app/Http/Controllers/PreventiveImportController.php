<?php

namespace App\Http\Controllers;

use App\Imports\PreventivesExcelImport;
use App\Models\Preventive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PreventiveImportController extends Controller
{
    public function showUpload()
    {
        return view('preventives.import-upload');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:20480'], // 20MB
        ]);

        $file = $request->file('excel_file');
        $tmpPath = $file->store('preventives_import_tmp', 'local');

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

        $import = new PreventivesExcelImport(dryRun: true);
        Excel::import($import, $file);

        Session::put('preventives_import_preview_debug', [
            'previewRows_count' => count($import->previewRows),
            'imported' => $import->imported,
            'skipped' => $import->skipped,
            'duplicates' => $import->duplicates,
            'failed' => $import->failed,
            'errors_sample' => array_slice($import->errors, 0, 5),
        ]);

        Session::put('preventives_import_preview', [
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

        Session::put('preventives_import_tmp_path', $tmpPath);

        return redirect()
            ->route('preventives.import.preview')
            ->with('success', 'Preview ready. Please confirm to import.');
    }

    public function confirmImport(Request $request)
    {
        $payload = Session::get('preventives_import_preview');
        if (!$payload || empty($payload['previewRows'])) {
            return redirect()
                ->route('preventives.import.upload')
                ->with('error', 'No preview data found. Please upload again.');
        }

        $tmpPath = Session::get('preventives_import_tmp_path');
        if (!$tmpPath) {
            return redirect()
                ->route('preventives.import.upload')
                ->with('error', 'Temporary upload expired. Please upload again.');
        }

        $import = new PreventivesExcelImport(dryRun: false);
        Excel::import($import, Storage::disk('local')->path($tmpPath));

        Session::forget('preventives_import_preview');
        Session::forget('preventives_import_tmp_path');
        Storage::disk('local')->delete($tmpPath);

        return redirect()
            ->route('preventives.index')
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
        return view('preventives.import-preview');
    }
}
