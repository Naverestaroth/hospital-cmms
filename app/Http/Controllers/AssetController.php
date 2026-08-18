<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    private array $statusList = [
        'berfungsi',
        'dalam perbaikan',
        'rusak',
        'proses penghapusan',
    ];

    public function index(Request $request)
    {
        $sortableColumns = ['room', 'asset_name', 'brand', 'type', 'serial_number', 'status'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns) && $sortField !== 'created_at') {
            $sortField = 'created_at';
        }

        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room')
            ->map(fn($r) => trim($r))
            ->unique()
            ->values();

        $selectedRoom = $request->input('room');

        $viewMode = $request->input('view', 'room');

        $baseQuery = Asset::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('asset_code', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhere('room', 'like', "%{$search}%");
                });
            })
            ->when($selectedRoom, function ($query, $room) {
                $query->where('room', $room);
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'rusak') {
                    $query->whereIn('status', ['rusak', 'Rusak', 'Broken', 'Tidak Berfungsi']);
                } elseif ($status === 'berfungsi') {
                    $query->whereIn('status', ['berfungsi', 'Berfungsi', 'Active']);
                } elseif ($status === 'dalam perbaikan') {
                    $query->whereIn('status', ['dalam perbaikan', 'Maintenance']);
                } elseif ($status === 'proses penghapusan') {
                    $query->whereIn('status', ['proses penghapusan', 'Proses Penghapusan']);
                } elseif ($status === 'other') {
                    $query->whereNotIn('status', [
                        'berfungsi', 'Berfungsi', 'Active',
                        'dalam perbaikan', 'Maintenance',
                        'rusak', 'Rusak', 'Broken', 'Tidak Berfungsi',
                        'proses penghapusan', 'Proses Penghapusan'
                    ]);
                } else {
                    $query->where('status', $status);
                }
            });

        if ($viewMode === 'room' || $request->input('sort') === 'room') {
            $baseQuery->orderBy('room', 'asc')->orderBy('asset_name', 'asc');
        } else {
            $baseQuery->orderBy($sortField, $sortDirection);
        }

        // Compute room pages for cross-page navigation
        $roomPages = [];
        $allRoomsOrdered = (clone $baseQuery)->pluck('room');
        foreach ($allRoomsOrdered as $index => $room) {
            $roomName = !empty(trim((string)$room)) ? trim((string)$room) : 'Unassigned / Ruangan Tidak Ditentukan';
            if (!isset($roomPages[$roomName])) {
                $roomPages[$roomName] = (int) floor($index / 15) + 1;
            }
        }
        ksort($roomPages);

        $assets = $baseQuery->paginate(15)->withQueryString();

        return view('assets.index', compact('assets', 'rooms', 'selectedRoom', 'sortField', 'sortDirection', 'roomPages'));
    }

    public function create()
    {
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        $statusList = $this->statusList;

        return view('assets.create', compact('rooms', 'statusList'));
    }

    public function store(Request $request)
    {
        if ($request->input('status_select') === 'Other') {
            $request->merge(['status' => $request->input('status_custom') ?: 'Other']);
        } elseif ($request->has('status_select') && !empty($request->input('status_select'))) {
            $request->merge(['status' => $request->input('status_select')]);
        }

        $validated = $request->validate([
            'asset_code' => 'required|unique:assets',
            'asset_name' => 'required',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'room' => 'required|string|max:255',
            'procurement_year' => ['nullable', 'regex:/^\d{4}$/'],
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (!empty($validated['procurement_year'])) {
            $year = (int) $validated['procurement_year'];
            $validated['procurement_year'] = $year . '-01-01';
        }

        Asset::create($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset created successfully.');
    }

    public function show(Asset $asset)
    {
        $asset->load([
            'tickets.technicians',
            'correctives',
            'preventives',
            'documents',
        ]);

        $stats = [
            'total_tickets' => $asset->tickets->count(),
            'total_correctives' => $asset->correctives->count(),
            'total_preventives' => $asset->preventives->count(),
            'last_repair_date' => $asset->correctives->max('repair_date'),
            'last_preventive_date' => $asset->preventives->max('schedule_date'),
        ];

        // Build combined chronological lifecycle timeline
        $timelineEvents = collect();

        foreach ($asset->tickets as $ticket) {
            $techNames = $ticket->technicians->pluck('name')->implode(', ');
            $timelineEvents->push([
                'type' => 'Ticket',
                'badge_class' => 'bg-blue-50 text-blue-700 border-blue-200',
                'date' => $ticket->created_at,
                'date_formatted' => $ticket->created_at->format('Y-m-d H:i'),
                'title' => "Ticket Created: {$ticket->ticket_code}",
                'subtitle' => "Reported by {$ticket->reported_by} • Priority: {$ticket->priority}",
                'description' => $ticket->issue,
                'status' => $ticket->status,
                'performers' => $techNames ?: 'Unassigned',
                'url' => route('tickets.show', $ticket),
            ]);
        }

        foreach ($asset->correctives as $corrective) {
            $techText = is_array($corrective->technician) ? implode(', ', $corrective->technician) : ($corrective->technician ?: 'N/A');
            $repairDate = $corrective->repair_date ? \Carbon\Carbon::parse($corrective->repair_date) : $corrective->created_at;
            $timelineEvents->push([
                'type' => 'Corrective',
                'badge_class' => 'bg-amber-50 text-amber-800 border-amber-200',
                'date' => $repairDate,
                'date_formatted' => $repairDate->format('Y-m-d'),
                'title' => "Corrective Maintenance Report",
                'subtitle' => "Problem: " . ($corrective->problem ?: 'N/A'),
                'description' => "Solution: " . ($corrective->solution ?: 'N/A'),
                'status' => $corrective->inspection_result ?: 'Completed',
                'performers' => $techText,
                'url' => route('correctives.show', $corrective),
            ]);
        }

        foreach ($asset->preventives as $preventive) {
            $techText = is_array($preventive->technician) ? implode(', ', $preventive->technician) : ($preventive->technician ?: 'N/A');
            $scheduleDate = $preventive->schedule_date ? \Carbon\Carbon::parse($preventive->schedule_date) : $preventive->created_at;
            $timelineEvents->push([
                'type' => 'Preventive',
                'badge_class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'date' => $scheduleDate,
                'date_formatted' => $scheduleDate->format('Y-m-d'),
                'title' => "Preventive Maintenance Scheduled",
                'subtitle' => "Condition: " . ($preventive->condition ?: 'Routine Check'),
                'description' => $preventive->notes ?: 'Scheduled preventive inspection.',
                'status' => $preventive->status ?: 'Completed',
                'performers' => $techText,
                'url' => route('preventives.show', $preventive),
            ]);
        }

        $timelineEvents = $timelineEvents->sortByDesc('date')->values();

        return view('assets.show', compact('asset', 'stats', 'timelineEvents'));
    }

    public function edit(Asset $asset)
    {
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        if ($asset->room && !$rooms->contains($asset->room)) {
            $rooms->push($asset->room);
        }

        $statusList = $this->statusList;

        return view('assets.edit', compact('asset', 'rooms', 'statusList'));
    }

    public function update(Request $request, Asset $asset)
    {
        if ($request->input('status_select') === 'Other') {
            $request->merge(['status' => $request->input('status_custom') ?: 'Other']);
        } elseif ($request->has('status_select') && !empty($request->input('status_select'))) {
            $request->merge(['status' => $request->input('status_select')]);
        }

        $validated = $request->validate([
            'asset_code' => 'required|unique:assets,asset_code,' . $asset->id,
            'asset_name' => 'required',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'room' => 'required|string|max:255',
            'procurement_year' => ['nullable', 'regex:/^\d{4}$/'],
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (!empty($validated['procurement_year'])) {
            $year = (int) $validated['procurement_year'];
            $validated['procurement_year'] = $year . '-01-01';
        }

        $asset->update($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Asset deleted successfully.');
    }

    public function downloadQr(Asset $asset)
    {
        $targetUrl = route('assets.show', $asset);
        $filename = ($asset->asset_code ?: ('AST-' . $asset->id)) . '-QR.png';

        try {
            $qrContent = QrCode::format('png')->size(300)->margin(1)->generate($targetUrl);
            return response($qrContent, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Throwable $e) {
            $svgFilename = ($asset->asset_code ?: ('AST-' . $asset->id)) . '-QR.svg';
            $qrContent = QrCode::format('svg')->size(300)->margin(1)->generate($targetUrl);
            return response($qrContent, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => 'attachment; filename="' . $svgFilename . '"',
            ]);
        }
    }

    public function printQr(Asset $asset)
    {
        $targetUrl = route('assets.show', $asset);
        $qrSvg = QrCode::size(200)->margin(1)->generate($targetUrl);

        return view('assets.qr-print', compact('asset', 'qrSvg'));
    }
}
