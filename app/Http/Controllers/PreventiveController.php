<?php

namespace App\Http\Controllers;

use App\Models\Preventive;
use App\Models\Asset;
use Illuminate\Http\Request;

class PreventiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['room', 'schedule_date', 'asset_name', 'brand', 'type', 'serial_number', 'status', 'technician'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns) && $sortField !== 'created_at') {
            $sortField = 'created_at';
        }

        $baseQuery = Preventive::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('technician', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortField, $sortDirection);

        // Compute room pages for cross-page room navigation
        $roomPages = [];
        $allRoomsOrdered = (clone $baseQuery)->pluck('room');
        foreach ($allRoomsOrdered as $index => $room) {
            $roomName = !empty(trim((string)$room)) ? trim((string)$room) : 'Unassigned / Ruangan Tidak Ditentukan';
            if (!isset($roomPages[$roomName])) {
                $roomPages[$roomName] = (int) floor($index / 15) + 1;
            }
        }
        ksort($roomPages);

        $preventives = $baseQuery->paginate(15)->withQueryString();

        return view('preventives.index', compact('preventives', 'sortField', 'sortDirection', 'roomPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        $assets = Asset::orderBy('asset_name')->get();
        $technicians = \App\Models\Technician::onDuty()->orderBy('name')->pluck('name');

        return view('preventives.create', compact('rooms', 'assets', 'technicians'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('schedule_date')) {
            $rawDate = trim((string) $request->schedule_date);
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $rawDate)) {
                try {
                    $formatted = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                    $request->merge(['schedule_date' => $formatted]);
                } catch (\Throwable $e) {}
            }
        }

        $request->validate([
            // Report
            'room' => 'nullable|string|max:255',
            'schedule_date' => 'nullable|date',

            // Snapshot Asset (all optional / nullable)
            'asset_code' => 'nullable|string|max:255',
            'asset_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'procurement_year' => ['nullable', 'regex:/^\d{4}$/'],

            // Other fields (all optional / nullable)
            'technician' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'status' => 'nullable|in:Scheduled,Completed,Missed',

            'notes' => 'nullable|string',
            'checklist' => 'nullable|array',
            'checklist.*' => 'nullable|string|max:255',
            'good_condition' => 'nullable|string',
            'problem_found' => 'nullable|string',
        ]);

        $payload = $request->all();
        unset($payload['asset_id']);

        if (empty($payload['schedule_date'])) {
            $payload['schedule_date'] = date('Y-m-d');
        }

        if (!empty($payload['procurement_year'])) {
            $year = (int) $payload['procurement_year'];
            $payload['procurement_year'] = $year . '-01-01';
        }

        Preventive::create($payload);

        return redirect()->route('preventives.index')->with('success', 'Preventive maintenance scheduled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Preventive $preventive)
    {
        return view('preventives.show', compact('preventive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Preventive $preventive)
    {
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        $technicians = \App\Models\Technician::onDuty()->orderBy('name')->pluck('name');
        if ($preventive->technician && !$technicians->contains($preventive->technician)) {
            $technicians->push($preventive->technician);
        }

        return view('preventives.edit', compact('preventive', 'rooms', 'technicians'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Preventive $preventive)
    {
        $validated = $request->validate([
            'room' => 'nullable|string|max:255',
            'schedule_date' => 'nullable|date',
            'asset_code' => 'nullable|string|max:255',
            'asset_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'procurement_year' => ['nullable', 'regex:/^\d{4}$/'],
            'technician' => 'nullable|string|max:255',
            'status' => 'nullable|in:Scheduled,Completed,Missed',
            'notes' => 'nullable|string',

            'condition' => 'nullable|string|max:255',
            'checklist' => 'nullable|array',
            'checklist.*' => 'nullable|string|max:255',
            'good_condition' => 'nullable|string',
            'problem_found' => 'nullable|string',
        ]);

        $payload = $validated;

        if (!empty($payload['procurement_year'])) {
            $year = (int) $payload['procurement_year'];
            $payload['procurement_year'] = $year . '-01-01';
        }

        $preventive->update($payload);

        return redirect()
            ->route('preventives.index')
            ->with('success', 'Preventive maintenance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preventive $preventive)
    {
        $preventive->delete();

        return redirect()
            ->route('preventives.index')
            ->with('success', 'Preventive maintenance deleted successfully.');
    }
}
