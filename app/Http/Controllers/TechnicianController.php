<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\TechnicianSchedule;
use App\Models\TechnicianScheduleException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TechnicianController extends Controller
{
    public function index(Request $request)
    {
        $allTechnicians = Technician::with([
            'schedules' => function ($q) {
                $q->orderBy('shift_date', 'desc');
            },
            'scheduleExceptions' => function ($q) {
                $q->orderBy('start_at', 'desc');
            },
            'tickets' => function ($q) {
                $q->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled', 'Completed'])->with('asset');
            }
        ])->orderBy('name')->get();

        $search = $request->input('search');
        if ($search) {
            $allTechnicians = $allTechnicians->filter(function ($t) use ($search) {
                return stripos($t->name, $search) !== false ||
                       stripos($t->email ?? '', $search) !== false ||
                       stripos($t->phone ?? '', $search) !== false;
            });
        }

        $filterStatus = $request->input('status', 'all');
        if ($filterStatus === 'on_duty') {
            $filteredTechnicians = $allTechnicians->filter(fn($t) => $t->duty_status === 'On Duty');
        } elseif ($filterStatus === 'off_duty') {
            $filteredTechnicians = $allTechnicians->filter(fn($t) => $t->duty_status === 'Off Duty');
        } else {
            $filteredTechnicians = $allTechnicians;
        }

        $onDutyCount  = Technician::all()->filter(fn($t) => $t->duty_status === 'On Duty')->count();
        $offDutyCount = Technician::all()->filter(fn($t) => $t->duty_status === 'Off Duty')->count();
        $totalCount   = Technician::count();

        $recentExceptions = TechnicianScheduleException::with('technician')->latest()->take(10)->get();
        $recentSchedules  = TechnicianSchedule::with('technician')->latest()->take(10)->get();

        return view('technicians.index', [
            'technicians' => $filteredTechnicians,
            'onDutyCount' => $onDutyCount,
            'offDutyCount' => $offDutyCount,
            'totalCount' => $totalCount,
            'filterStatus' => $filterStatus,
            'recentExceptions' => $recentExceptions,
            'recentSchedules' => $recentSchedules,
            'allTechsForSelect' => Technician::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function show($id)
    {
        $technician = Technician::with(['tickets' => fn ($q) => $q->with('asset'), 'user', 'schedules', 'scheduleExceptions'])
            ->findOrFail($id);

        $activeTickets = $technician->tickets()
            ->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled', 'Completed'])
            ->with('asset')->latest('ticket_technician.created_at')->get();

        $completedTickets = $technician->tickets()
            ->whereIn('status', ['Closed', 'Completed'])
            ->with('asset')->latest('ticket_technician.created_at')->get();

        return view('technicians.show', compact('technician', 'activeTickets', 'completedTickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'duty_status' => 'required|in:On Duty,Off Duty',
        ]);

        $technician = Technician::create($validated);

        return redirect()->route('technicians.index')
            ->with('success', "Technician {$technician->name} created successfully.");
    }

    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'duty_status'     => 'nullable|in:On Duty,Off Duty',
            'manual_override' => 'nullable|in:On Duty,Off Duty,auto',
        ]);

        $override = $request->input('manual_override');
        if ($override === 'auto' || empty($override)) {
            $validated['manual_override'] = null;
        } else {
            $validated['manual_override'] = $override;
        }

        $technician->update($validated);

        return redirect()->route('technicians.index')
            ->with('success', "Technician {$technician->name} updated successfully.");
    }

    public function toggleOverride(Request $request, Technician $technician)
    {
        $request->validate([
            'manual_override' => 'nullable|in:On Duty,Off Duty,auto',
        ]);

        $override = $request->input('manual_override');
        if ($override === 'auto') {
            $override = null;
        }

        $technician->update(['manual_override' => $override]);

        return redirect()->back()
            ->with('success', "Duty status override updated for {$technician->name}.");
    }

    public function importSchedule(Request $request)
    {
        $request->validate([
            'schedule_file' => 'nullable|file|mimes:xlsx,xls,csv,txt|max:10240',
            'schedule_text' => 'nullable|string',
        ]);

        $rawRows = [];
        $fullText = '';

        if ($request->hasFile('schedule_file')) {
            $file = $request->file('schedule_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $realPath = $file->getRealPath() ?: $file->getPathname();

            try {
                if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($realPath);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

                    foreach ($sheetData as $r) {
                        $rowVals = array_map(function ($val) {
                            return $val !== null ? trim((string)$val) : '';
                        }, $r);
                        if (array_filter($rowVals, fn($v) => $v !== '')) {
                            $rawRows[] = array_values($rowVals);
                            $fullText .= ' ' . implode(' ', $rowVals);
                        }
                    }
                }
            } catch (\Exception $e) {}

            if (empty($rawRows) && file_exists($realPath)) {
                $content = file_get_contents($realPath);
                $lines = explode("\n", str_replace("\r", "", $content));
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!$line) continue;
                    $row = str_contains($line, ',') ? str_getcsv($line) : preg_split('/\s+/', $line);
                    $row = array_map('trim', $row);
                    $rawRows[] = $row;
                    $fullText .= ' ' . $line;
                }
            }
        } elseif ($request->filled('schedule_text')) {
            $lines = explode("\n", str_replace("\r", "", $request->input('schedule_text')));
            foreach ($lines as $line) {
                $line = trim($line);
                if (!$line) continue;
                $row = str_contains($line, ',') ? str_getcsv($line) : preg_split('/\s+/', $line);
                $row = array_map('trim', $row);
                $rawRows[] = $row;
                $fullText .= ' ' . $line;
            }
        } else {
            return redirect()->back()->with('error', 'Please upload an Excel file (.xlsx, .xls, .csv) or paste schedule text.');
        }

        if (empty($rawRows)) {
            return redirect()->back()->with('error', 'No valid schedule data found in Excel.');
        }

        // Process both Matrix and Detail formats to support Hybrid files
        $matrixCount = $this->parseMatrixGridFromRows($rawRows, $fullText);
        $detailCount = $this->parseDetailFromRows($rawRows);
        $totalCount  = $matrixCount + $detailCount;

        if ($totalCount > 0) {
            $msg = "Successfully imported {$totalCount} schedule entries from Excel";
            if ($matrixCount > 0 && $detailCount > 0) {
                $msg .= " (Hybrid Matrix + Detail Format).";
            } elseif ($matrixCount > 0) {
                $msg .= " (Matrix Format).";
            } else {
                $msg .= " (Detail Format).";
            }
            return redirect()->back()->with('success', $msg);
        }

        return redirect()->back()->with('error', 'No matching technician schedule entries found in Excel.');
    }

    private function parseMatrixGridFromRows(array $rawRows, string $fullText): int
    {
        $matrixHeaderIndex = -1;
        $dayColumns = [];

        foreach ($rawRows as $idx => $row) {
            $numericCols = 0;
            $expandedRow = [];
            foreach ($row as $col) {
                if (is_numeric($col) && (int)$col >= 1 && (int)$col <= 31) {
                    $numericCols++;
                    $expandedRow[] = (int)$col;
                } elseif (preg_match('/^[1-9]{3,}$/', $col)) {
                    foreach (str_split($col) as $d) {
                        $numericCols++;
                        $expandedRow[] = (int)$d;
                    }
                } else {
                    $expandedRow[] = $col;
                }
            }
            if ($numericCols >= 3) {
                $matrixHeaderIndex = $idx;
                $dayColumns = $expandedRow;
                break;
            }
        }

        if ($matrixHeaderIndex === -1 || empty($dayColumns)) {
            return 0;
        }

        $monthMap = [
            'januari' => 1, 'january' => 1, 'februari' => 2, 'february' => 2,
            'maret' => 3, 'march' => 3, 'april' => 4, 'mei' => 5, 'may' => 5,
            'juni' => 6, 'june' => 6, 'juli' => 7, 'july' => 7, 'agustus' => 8, 'august' => 8,
            'september' => 9, 'oktober' => 10, 'october' => 10, 'november' => 11, 'desember' => 12, 'december' => 12
        ];

        $year = (int)date('Y');
        if (preg_match('/20\d{2}/', $fullText, $yMatch)) {
            $year = (int)$yMatch[0];
        }

        $detectedMonths = [];
        foreach ($monthMap as $mName => $mNum) {
            if (stripos($fullText, $mName) !== false) {
                $detectedMonths[] = $mNum;
            }
        }
        $detectedMonths = array_values(array_unique($detectedMonths));

        $firstMonth  = $detectedMonths[0] ?? (int)date('n');
        $secondMonth = $detectedMonths[1] ?? $firstMonth;

        $columnDates = [];
        $currentMonth = $firstMonth;
        $prevDay = -1;

        foreach ($dayColumns as $cIdx => $colVal) {
            if (!is_numeric($colVal)) continue;
            $dayNum = (int)$colVal;
            if ($prevDay !== -1 && $dayNum < $prevDay && $firstMonth !== $secondMonth) {
                $currentMonth = $secondMonth;
            }
            $prevDay = $dayNum;
            $columnDates[$cIdx] = sprintf('%04d-%02d-%02d', $year, $currentMonth, $dayNum);
        }

        if (empty($columnDates)) {
            return 0;
        }

        $allTechs = Technician::all();
        $importedCount = 0;
        $orderedDates = array_values($columnDates);
        $dayColIndices = array_keys($columnDates);

        for ($i = $matrixHeaderIndex + 1; $i < count($rawRows); $i++) {
            $row = $rawRows[$i];
            if (count($row) < 2) continue;

            $firstCell = strtolower(trim($row[0] ?? ''));
            if (in_array($firstCell, ['keterangan', 'keterangan:', 'catatan', 'note', 'legend']) || str_contains($firstCell, 'keterangan')) {
                break;
            }

            $matchedTech = null;
            $lastTechTokenIdx = -1;

            for ($start = 0; $start < min(3, count($row)); $start++) {
                $combined = '';
                for ($end = $start; $end < min(count($row), $start + 4); $end++) {
                    $combined = trim($combined . ' ' . ($row[$end] ?? ''));
                    if (!$combined) continue;

                    $found = $allTechs->first(function ($t) use ($combined) {
                        return strcasecmp($t->name, $combined) === 0 ||
                               ($t->email && strcasecmp($t->email, $combined) === 0) ||
                               (strlen($combined) >= 4 && stripos($t->name, $combined) !== false);
                    });

                    if ($found) {
                        $matchedTech = $found;
                        $lastTechTokenIdx = $end;
                    }
                }
                if ($matchedTech) break;
            }

            if (!$matchedTech) continue;

            // If cell immediately following technician name is a valid date (e.g. 2026-08-26 or 26/08/2026),
            // this row is a detail table row, not a matrix grid row. Skip matrix parsing for this row.
            $nextCell = trim((string)($row[$lastTechTokenIdx + 1] ?? ''));
            if ($nextCell && (preg_match('/^\d{4}[-\/]\d{1,2}[-\/]\d{1,2}/', $nextCell) || preg_match('/^\d{1,2}[-\/]\d{1,2}[-\/]\d{4}/', $nextCell))) {
                continue;
            }

            foreach ($orderedDates as $dayOffset => $shiftDate) {
                $cellIdx = null;
                $relativeIdx = $lastTechTokenIdx + 1 + $dayOffset;

                if (isset($row[$relativeIdx])) {
                    $cellIdx = $relativeIdx;
                } elseif (isset($dayColIndices[$dayOffset]) && isset($row[$dayColIndices[$dayOffset]])) {
                    $cellIdx = $dayColIndices[$dayOffset];
                }

                if ($cellIdx !== null && isset($row[$cellIdx])) {
                    $shiftCode = trim((string)$row[$cellIdx]);
                    if ($shiftCode === '') continue;

                    try {
                        $parsedDate = Carbon::parse($shiftDate)->format('Y-m-d');
                        $shiftInfo  = $this->resolveShiftTimes($shiftCode, $shiftCode);

                        $existing = TechnicianSchedule::where('technician_id', $matchedTech->id)
                            ->whereDate('shift_date', $parsedDate)
                            ->first();

                        if ($existing) {
                            $existing->update([
                                'shift_name' => $shiftInfo['shift_name'],
                                'start_time' => $shiftInfo['start_time'],
                                'end_time'   => $shiftInfo['end_time'],
                            ]);
                        } else {
                            TechnicianSchedule::create([
                                'technician_id' => $matchedTech->id,
                                'shift_date'    => $parsedDate,
                                'shift_name'    => $shiftInfo['shift_name'],
                                'start_time'    => $shiftInfo['start_time'],
                                'end_time'      => $shiftInfo['end_time'],
                            ]);
                        }
                        $importedCount++;
                    } catch (\Exception $e) {}
                }
            }
        }

        return $importedCount;
    }

    private function parseDetailFromRows(array $rawRows): int
    {
        $allTechs = Technician::all();
        $importedCount = 0;

        foreach ($rawRows as $row) {
            if (count($row) < 2) continue;

            $techIdentifier = trim($row[0] ?? '');
            $shiftDate      = trim($row[1] ?? '');
            $timeOrCode     = isset($row[2]) ? trim($row[2]) : '';
            $endTimeInput   = isset($row[3]) ? trim($row[3]) : '';
            $shiftNameInput = isset($row[4]) ? trim($row[4]) : '';

            if (empty($techIdentifier) || empty($shiftDate)) continue;

            $tech = $allTechs->first(function ($t) use ($techIdentifier) {
                return strcasecmp($t->name, $techIdentifier) === 0 ||
                       ($t->email && strcasecmp($t->email, $techIdentifier) === 0) ||
                       (is_numeric($techIdentifier) && $t->id == (int)$techIdentifier);
            });

            if ($tech) {
                try {
                    $parsedDate = null;
                    if (preg_match('/^(\d{1,2})[\/\.-](\d{1,2})[\/\.-](\d{4})$/', $shiftDate, $m)) {
                        $d = (int)$m[1];
                        $mVal = (int)$m[2];
                        $y = (int)$m[3];

                        if ($d > 12) {
                            $parsedDate = sprintf('%04d-%02d-%02d', $y, $mVal, $d);
                        } elseif ($mVal > 12) {
                            $parsedDate = sprintf('%04d-%02d-%02d', $y, $d, $mVal);
                        } else {
                            $parsedDate = sprintf('%04d-%02d-%02d', $y, $mVal, $d);
                        }
                    } else {
                        $parsedDate = Carbon::parse($shiftDate)->format('Y-m-d');
                    }

                    $isTime = fn($s) => preg_match('/^\d{1,2}[:\.]\d{2}/', trim($s));
                    if ($isTime($endTimeInput) && $isTime($shiftNameInput)) {
                        $shiftInfo = $this->resolveShiftTimes($timeOrCode, $endTimeInput, $shiftNameInput);
                    } else {
                        $shiftInfo = $this->resolveShiftTimes($shiftNameInput ?: $timeOrCode, $timeOrCode, $endTimeInput);
                    }

                    $existing = TechnicianSchedule::where('technician_id', $tech->id)
                        ->whereDate('shift_date', $parsedDate)
                        ->first();

                    if ($existing) {
                        $existing->update([
                            'shift_name' => $shiftInfo['shift_name'],
                            'start_time' => $shiftInfo['start_time'],
                            'end_time'   => $shiftInfo['end_time'],
                        ]);
                    } else {
                        TechnicianSchedule::create([
                            'technician_id' => $tech->id,
                            'shift_date'    => $parsedDate,
                            'shift_name'    => $shiftInfo['shift_name'],
                            'start_time'    => $shiftInfo['start_time'],
                            'end_time'      => $shiftInfo['end_time'],
                        ]);
                    }
                    $importedCount++;
                } catch (\Exception $e) {}
            }
        }

        return $importedCount;
    }

    private function resolveShiftTimes(string $shiftName, string $startTimeInput = '', string $endTimeInput = ''): array
    {
        $cleanName  = trim($shiftName);
        $lowerName  = strtolower($cleanName);
        $lowerStart = strtolower(trim($startTimeInput));

        if (in_array($lowerName, ['off', 'libur', 'l', 'cuti', 'izin', 'jadwal off']) || in_array($lowerStart, ['off', 'libur', 'l'])) {
            return [
                'shift_name' => $cleanName ?: 'Jadwal Off',
                'start_time' => '00:00:00',
                'end_time'   => '00:00:00',
            ];
        }

        $parseTime = function($str) {
            $str = trim($str);
            if (preg_match('/^(\d{1,2})[:\.](\d{2})(?:[:\.](\d{2}))?$/', $str, $m)) {
                return sprintf('%02d:%02d:%02d', $m[1], $m[2], $m[3] ?? 0);
            }
            return null;
        };

        $parseRange = function($str) {
            $str = trim($str);
            if (preg_match('/^(\d{1,2})[:\.](\d{2})\s*[-–]\s*(\d{1,2})[:\.](\d{2})$/', $str, $m)) {
                return [
                    sprintf('%02d:%02d:00', $m[1], $m[2]),
                    sprintf('%02d:%02d:00', $m[3], $m[4]),
                ];
            }
            return null;
        };

        $start = '';
        $end   = '';

        $range = $parseRange($startTimeInput) ?? $parseRange($cleanName);
        if ($range) {
            $start = $range[0];
            $end   = $range[1];
        } elseif ($parsedStart = $parseTime($startTimeInput)) {
            $start = $parsedStart;
            if ($parsedEnd = $parseTime($endTimeInput)) {
                $end = $parsedEnd;
            } else {
                $end = '17:00:00';
            }
        } else {
            $checkStr = $lowerName ?: $lowerStart;
            if (str_contains($checkStr, 'pagi') || $checkStr === 'p') {
                $start = '08:00:00';
                $end   = '16:00:00';
                if (!$cleanName || strcasecmp($cleanName, 'p') === 0) $cleanName = 'Shift Pagi';
            } elseif (str_contains($checkStr, 'siang') || $checkStr === 's') {
                $start = '14:00:00';
                $end   = '22:00:00';
                if (!$cleanName || strcasecmp($cleanName, 's') === 0) $cleanName = 'Shift Siang';
            } elseif (str_contains($checkStr, 'malam') || $checkStr === 'm') {
                $start = '22:00:00';
                $end   = '06:00:00';
                if (!$cleanName || strcasecmp($cleanName, 'm') === 0) $cleanName = 'Shift Malam';
            } else {
                $start = '08:00:00';
                $end   = '17:00:00';
                if (!$cleanName) $cleanName = 'Shift Regular';
            }
        }

        return [
            'shift_name' => $cleanName ?: 'Shift Regular',
            'start_time' => $start,
            'end_time'   => $end,
        ];
    }



    public function storeException(Request $request)
    {
        foreach (['start_at', 'end_at'] as $field) {
            if ($val = $request->input($field)) {
                try {
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}/', trim($val))) {
                        $parsed = Carbon::createFromFormat('d/m/Y H:i', trim($val));
                    } else {
                        $parsed = Carbon::parse(trim($val));
                    }
                    $request->merge([$field => $parsed->toDateTimeString()]);
                } catch (\Exception $e) {}
            }
        }

        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'type'          => 'required|string|in:Lembur,Izin,Sakit,Cuti',
            'start_at'      => 'required|date',
            'end_at'        => 'required|date|after_or_equal:start_at',
            'notes'         => 'nullable|string',
        ]);

        $overrideStatus = ($validated['type'] === 'Lembur') ? 'On Duty' : 'Off Duty';

        TechnicianScheduleException::create([
            'technician_id'   => $validated['technician_id'],
            'type'            => $validated['type'],
            'override_status' => $overrideStatus,
            'start_at'        => $validated['start_at'],
            'end_at'          => $validated['end_at'],
            'notes'           => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', "Schedule exception ({$validated['type']}) recorded successfully.");
    }

    public function destroyException($id)
    {
        $exception = TechnicianScheduleException::findOrFail($id);
        $exception->delete();

        return redirect()->back()->with('success', "Schedule exception removed successfully.");
    }

    public function dutyStatuses()
    {
        $technicians = Technician::with(['schedules', 'scheduleExceptions'])->get();
        $statuses = [];
        $onDutyCount = 0;
        $offDutyCount = 0;

        foreach ($technicians as $tech) {
            $resolved = $tech->resolveDutyState();
            if ($resolved['status'] === 'On Duty') {
                $onDutyCount++;
            } else {
                $offDutyCount++;
            }

            $statuses[$tech->id] = [
                'id'                => $tech->id,
                'duty_status'       => $resolved['status'],
                'duty_source_label' => $resolved['source'],
            ];
        }

        return response()->json([
            'statuses'     => $statuses,
            'onDutyCount'  => $onDutyCount,
            'offDutyCount' => $offDutyCount,
            'totalCount'   => $technicians->count(),
        ]);
    }
}
