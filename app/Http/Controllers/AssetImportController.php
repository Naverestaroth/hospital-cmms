<?php

namespace App\Http\Controllers;

use App\Imports\AssetsExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AssetImportController extends Controller
{
    public function showUpload()
    {
        return view('assets.import-upload');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:20480'], // 20MB
        ]);

        $file = $request->file('excel_file');

        // Store temp file so confirm step can import the same file.
        $tmpPath = $file->store('assets_import_tmp', 'local');

        // Generate next codes safely by reading existing max.
        $existingMax = (int) \App\Models\Asset::query()
            ->selectRaw('MAX(CAST(SUBSTRING(asset_code, 5) AS UNSIGNED)) as m')
            ->value('m');

        $existingMax = $existingMax > 0 ? $existingMax : 0;
        $codeSeed = $existingMax + 1;

        /*
         |------------------------------------------------------------
         | DEBUG: Raw parse (do not apply mapping/logic)
         |------------------------------------------------------------
         | We need to know how Laravel Excel parses the sheet:
         | - sheet name(s)
         | - heading row values (row 1)
         | - parsed keys/indexes visible in the first row
         | - first data row
         */
        $debug = [];

        try {
            $collections = Excel::toCollection(null, $file);

            $sheetNames = [];
            foreach ($collections as $sheetIndex => $sheetRows) {
                $sheetNames[] = (string) ($sheetIndex + 1);
            }

            $sheetIndex = 0;
            /** @var \Illuminate\Support\Collection $firstSheet */
            $firstSheet = $collections[$sheetIndex] ?? collect();

            $headingRow = $firstSheet[0] ?? null;

            // Attempt to capture "keys" Laravel Excel exposes for the heading row.
            // Usually ToCollection returns a Collection of rows where each row is an array.
            $parsedColumnKeys = [];
            if (is_array($headingRow)) {
                $parsedColumnKeys = array_keys($headingRow);
            } elseif ($headingRow instanceof \Illuminate\Contracts\Support\Arrayable) {
                $arr = $headingRow->toArray();
                $parsedColumnKeys = array_keys($arr);
            }

            $firstDataRow = $firstSheet[1] ?? null;

            $debug = [
                'sheet_names' => $sheetNames,
                'heading_row_1_values' => $headingRow,
                'parsed_column_keys_heading_row_1' => $parsedColumnKeys,
                'first_data_row_2_values' => $firstDataRow,
            ];
        } catch (\Throwable $e) {
            $debug = [
                'parse_error' => $e->getMessage(),
            ];
        }

        // Run your existing importer logic for preview (mapping/validation).
        $import = new AssetsExcelImport(dryRun: true, codeSeed: $codeSeed);

        // IMPORTANT: Preview must NOT write anything to DB.
        Excel::import($import, $file);

        // Extra debug to understand why previewRows is empty
        // (This does not write to DB; it only helps diagnose parsing/mapping.)
        Session::put('assets_import_preview_debug', [
            'previewRows_count' => is_array($import->previewRows) ? count($import->previewRows) : null,
            'imported' => $import->imported,
            'skipped' => $import->skipped,
            'updated' => $import->updated,
            'failed' => $import->failed,
            'errors_sample' => array_slice($import->errors ?? [], 0, 5),
        ]);

        Session::put('assets_import_preview', [
            'previewRows' => $import->previewRows,
            'summary' => [
                'imported' => $import->imported, // 0 in dry-run
                'skipped' => $import->skipped,
                'updated' => $import->updated,
                'failed' => $import->failed,
            ],
            'errors' => $import->errors,
            // new: raw debug payload
            'debug_excel_parse' => $debug,
        ]);

        Session::put('assets_import_tmp_path', $tmpPath);

        return redirect()
            ->route('assets.import.preview')
            ->with('success', 'Preview ready. Please confirm to import.');
    }

    public function confirmImport(Request $request)
    {
        $payload = Session::get('assets_import_preview');

        if (!$payload || empty($payload['previewRows'])) {
            return redirect()
                ->route('assets.import.upload')
                ->with('error', 'No preview data found. Please upload an Excel file again.');
        }

        $tmpPath = Session::get('assets_import_tmp_path');
        if (!$tmpPath) {
            return redirect()
                ->route('assets.import.upload')
                ->with('error', 'Temporary upload expired. Please upload again.');
        }

        $existingMax = (int) \App\Models\Asset::query()
            ->selectRaw('MAX(CAST(SUBSTRING(asset_code, 5) AS UNSIGNED)) as m')
            ->value('m');

        $existingMax = $existingMax > 0 ? $existingMax : 0;
        $codeSeed = $existingMax + 1;

        $import = new AssetsExcelImport(dryRun: false, codeSeed: $codeSeed);

        Excel::import(
            $import,
            Storage::disk('local')->path($tmpPath)
        );

        Session::forget('assets_import_preview');
        Session::forget('assets_import_tmp_path');

        // Cleanup temp file best-effort
        Storage::disk('local')->delete($tmpPath);

        return redirect()
            ->route('assets.index')
            ->with('success', sprintf(
                'Excel import completed. Imported: %d, Updated: %d, Skipped: %d, Failed: %d.',
                $import->imported,
                $import->updated,
                $import->skipped,
                $import->failed
            ));
    }

    public function previewPage()
    {
        return view('assets.import-preview');
    }
}
