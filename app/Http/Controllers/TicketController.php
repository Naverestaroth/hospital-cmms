<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\Technician;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $sortableColumns = ['ticket_code', 'reported_by', 'priority', 'status', 'created_at'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns)) {
            $sortField = 'created_at';
        }

        $query = Ticket::with(['asset', 'technicians']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('reported_by', 'like', "%{$search}%")
                    ->orWhere('priority', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('room', 'like', "%{$search}%")
                    ->orWhereHas('asset', function ($assetQuery) use ($search) {
                        $assetQuery->where('asset_name', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('technicians', function ($techQuery) use ($search) {
                        $techQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $st = $request->status;
            if (strtolower($st) === 'open') {
                $query->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled']);
            } else {
                $query->where('status', $st);
            }
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $baseQuery = $query->orderBy($sortField, $sortDirection);

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

        $tickets = $baseQuery->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Ticket::count(),
            'waiting_approval' => Ticket::where('status', 'Waiting Approval')->count(),
            'open' => Ticket::whereIn('status', ['Open', 'Approved'])->count(),
            'assigned' => Ticket::where('status', 'Assigned')->count(),
            'in_progress' => Ticket::whereIn('status', ['Accepted', 'In Progress', 'Waiting Sparepart', 'Waiting Vendor', 'Waiting User'])->count(),
            'completed' => Ticket::whereIn('status', ['Repair Completed', 'Waiting Corrective Report', 'Corrective Report Completed'])->count(),
            'closed' => Ticket::where('status', 'Closed')->count(),
        ];

        return view('tickets.index', compact('tickets', 'sortField', 'sortDirection', 'statusCounts', 'roomPages'));
    }

    public function create()
    {
        // Dynamic Room Dropdown: Queries distinct rooms directly from Asset inventory database
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        // Only show technicians based on On Duty status
        $technicians = Technician::onDuty()->orderBy('name')->get();
        $assets = Asset::orderBy('asset_name')->get();

        return view('tickets.create', compact('rooms', 'technicians', 'assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room'                   => 'required|string',
            'asset_id'               => 'required|exists:assets,id',
            'reported_by'            => 'required|string|max:100',
            'creator_type'           => 'required|in:User,Technician',
            'issue'                  => 'required|string',
            'priority'               => 'required|in:Low,Medium,High',
            'equipment_completeness' => 'nullable|string',
            'sent_to_workshop_date'  => 'nullable|date',
            'sent_by'                => 'nullable|string|max:255',
            'received_by_workshop'   => 'nullable|string|max:255',
            'returned_date'          => 'nullable|date',
            'returned_by'            => 'nullable|string|max:255',
            'received_by_user'       => 'nullable|string|max:255',
            'technician_ids'         => 'nullable|array',
            'technician_ids.*'       => 'exists:technicians,id',
        ]);

        $room = $request->room;
        $asset = Asset::find($request->asset_id);
        if ($asset && !empty($asset->room)) {
            $room = $asset->room;
        }

        $ticketCode = 'TCK-' . strtoupper(substr(uniqid(), -6));

        $ticket = Ticket::create([
            'ticket_code'            => $ticketCode,
            'asset_id'               => $request->asset_id,
            'room'                   => $room,
            'reported_by'            => $request->reported_by,
            'creator_type'           => $request->creator_type,
            'issue'                  => $request->issue,
            'priority'               => $request->priority,
            'equipment_completeness' => $request->equipment_completeness,
            'sent_to_workshop_date'  => $request->sent_to_workshop_date,
            'sent_by'                => $request->sent_by,
            'received_by_workshop'   => $request->received_by_workshop,
            'returned_date'          => $request->returned_date,
            'returned_by'            => $request->returned_by,
            'received_by_user'       => $request->received_by_user,
            'status'                 => 'Waiting Approval',
        ]);

        // Log Timeline: Ticket Created
        $ticket->logActivity('Ticket Created', $request->reported_by, "Ticket created via {$request->creator_type} portal.");

        // Pre-select Technicians if specified upon creation
        if (!empty($request->technician_ids)) {
            $syncData = [];
            foreach ($request->technician_ids as $techId) {
                $syncData[$techId] = [
                    'assignment_type' => 'assigned',
                    'assigned_at' => now(),
                ];
            }
            $ticket->technicians()->sync($syncData);

            $techNames = Technician::whereIn('id', $request->technician_ids)->pluck('name')->toArray();
            $ticket->logActivity('Pre-assigned Technicians', $request->reported_by, 'Pre-assigned to ' . implode(', ', $techNames));
        }

        return redirect()
            ->route('tickets.index')
            ->with('success', "Ticket {$ticketCode} created successfully and is waiting for approval.");
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['asset', 'technicians', 'activities', 'workLogs']);
        $technicians = Technician::onDuty()->orderBy('name')->get();

        return view('tickets.show', compact('ticket', 'technicians'));
    }

    public function edit(Ticket $ticket)
    {
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        $assignedTechIds = $ticket->technicians->pluck('id')->toArray();
        $technicians = Technician::onDuty()
            ->orWhereIn('id', $assignedTechIds)
            ->orderBy('name')
            ->get();

        $assets = Asset::orderBy('asset_name')->get();
        $ticket->load('technicians');

        return view('tickets.edit', compact('ticket', 'rooms', 'technicians', 'assets'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'room'                   => 'required|string',
            'asset_id'               => 'required|exists:assets,id',
            'reported_by'            => 'required|string|max:100',
            'creator_type'           => 'required|in:User,Technician',
            'issue'                  => 'required|string',
            'priority'               => 'required|in:Low,Medium,High',
            'equipment_completeness' => 'nullable|string',
            'sent_to_workshop_date'  => 'nullable|date',
            'sent_by'                => 'nullable|string|max:255',
            'received_by_workshop'   => 'nullable|string|max:255',
            'returned_date'          => 'nullable|date',
            'returned_by'            => 'nullable|string|max:255',
            'received_by_user'       => 'nullable|string|max:255',
            'technician_ids'         => 'nullable|array',
            'technician_ids.*'       => 'exists:technicians,id',
        ]);

        $ticket->update([
            'asset_id'               => $request->asset_id,
            'room'                   => $request->room,
            'reported_by'            => $request->reported_by,
            'creator_type'           => $request->creator_type,
            'issue'                  => $request->issue,
            'priority'               => $request->priority,
            'equipment_completeness' => $request->equipment_completeness,
            'sent_to_workshop_date'  => $request->sent_to_workshop_date,
            'sent_by'                => $request->sent_by,
            'received_by_workshop'   => $request->received_by_workshop,
            'returned_date'          => $request->returned_date,
            'returned_by'            => $request->returned_by,
            'received_by_user'       => $request->received_by_user,
        ]);

        if ($request->has('technician_ids')) {
            $techIds = $request->technician_ids ?? [];
            $syncData = [];
            foreach ($techIds as $techId) {
                $syncData[$techId] = [
                    'assignment_type' => 'assigned',
                    'assigned_at' => now(),
                ];
            }
            $ticket->technicians()->sync($syncData);

            $techNames = Technician::whereIn('id', $techIds)->pluck('name')->toArray();
            $ticket->logActivity('Updated Technicians', auth()->user()?->name ?? $request->reported_by, 'Assigned to ' . (count($techNames) ? implode(', ', $techNames) : 'None'));
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    // JSON API: Fetch assets by selected room for dynamic dependent dropdowns
    public function assetsByRoom(Request $request)
    {
        $room = $request->input('room');
        $assets = Asset::query()
            ->when($room, function ($q) use ($room) {
                $q->where('room', $room);
            })
            ->orderBy('asset_name')
            ->get(['id', 'asset_code', 'asset_name', 'brand', 'type', 'serial_number', 'room']);

        return response()->json($assets);
    }

    // Workflow Actions: Approve (Simple workflow step action)
    public function approve(Request $request, Ticket $ticket)
    {
        if ($ticket->status !== 'Waiting Approval') {
            return redirect()->back()->with('error', 'This ticket is not waiting for approval.');
        }

        $nextStatus = $ticket->technicians()->count() > 0 ? 'Assigned' : 'Open';
        $ticket->update([
            'status' => $nextStatus,
            'approved_at' => now(),
            'approved_by' => 'IPSRS Coordinator',
        ]);

        $ticket->logActivity('Approved Ticket', 'IPSRS Coordinator', 'Ticket approved and opened for maintenance.');

        return redirect()
            ->back()
            ->with('success', "Ticket approved successfully. Status updated to {$nextStatus}.");
    }

    // Workflow Actions: Reject
    public function reject(Request $request, Ticket $ticket)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $ticket->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => 'IPSRS Coordinator',
        ]);

        $ticket->logActivity('Rejected Ticket', 'IPSRS Coordinator', "Reason: {$request->rejection_reason}");

        return redirect()
            ->back()
            ->with('success', 'Ticket has been rejected.');
    }

    // Workflow Actions: Assign Technicians
    public function assignTechnicians(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'Waiting Approval') {
            return redirect()->back()->with('error', 'Tickets waiting for approval cannot be assigned yet. Please approve the ticket first.');
        }

        $request->validate([
            'technician_ids' => 'required|array|min:1',
            'technician_ids.*' => 'exists:technicians,id',
        ]);

        $syncData = [];
        foreach ($request->technician_ids as $techId) {
            $syncData[$techId] = [
                'assignment_type' => 'assigned',
                'assigned_at' => now(),
            ];
        }
        $ticket->technicians()->sync($syncData);

        if (in_array($ticket->status, ['Open', 'Approved'])) {
            $ticket->update(['status' => 'Assigned']);
        }

        $techNames = Technician::whereIn('id', $request->technician_ids)->pluck('name')->toArray();
        $ticket->logActivity('Assigned Technicians', 'IPSRS Coordinator', 'Assigned to ' . implode(', ', $techNames));

        return redirect()
            ->back()
            ->with('success', 'Technicians assigned successfully.');
    }

    // Workflow Actions: Self Assign
    public function selfAssign(Request $request, Ticket $ticket)
    {
        if ($ticket->status === 'Waiting Approval') {
            return redirect()->back()->with('error', 'Tickets waiting for approval cannot be self-assigned yet. Please approve the ticket first.');
        }

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::findOrFail($request->technician_id);

        // Attach technician into pivot without overwriting existing assigned technicians
        $ticket->technicians()->syncWithoutDetaching([
            $technician->id => [
                'assignment_type' => 'self',
                'assigned_at' => now(),
            ],
        ]);

        if (in_array($ticket->status, ['Open', 'Approved'])) {
            $ticket->update(['status' => 'Assigned']);
        }

        $ticket->logActivity('Self Assigned', $technician->name, "Self Assigned by {$technician->name}");

        return redirect()
            ->back()
            ->with('success', "{$technician->name} self-assigned to this ticket.");
    }

    // Workflow Actions: Accept Ticket
    public function accept(Request $request, Ticket $ticket)
    {
        $ticket->update(['status' => 'Accepted']);

        $performer = $request->input('technician_name', 'Assigned Technician');
        $ticket->logActivity('Accepted Ticket', $performer, 'Ticket accepted by technician.');

        return redirect()
            ->back()
            ->with('success', 'Ticket status updated to Accepted.');
    }

    // Workflow Actions: Update Intermediate Status
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $allowedStatuses = [
            'In Progress',
            'Waiting Sparepart',
            'Waiting Vendor',
            'Waiting User',
            'Repair Completed',
            'Waiting Corrective Report',
            'Closed',
            'Cancelled',
        ];

        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        $ticket->update(['status' => $newStatus]);

        $performer = 'IPSRS Team';
        $ticket->logActivity("Status Changed to {$newStatus}", $performer, $request->notes);

        return redirect()
            ->back()
            ->with('success', "Ticket status changed from {$oldStatus} to {$newStatus}.");
    }

    // Workflow Actions: Close Ticket
    public function close(Request $request, Ticket $ticket)
    {
        $ticket->update(['status' => 'Closed']);
        $ticket->logActivity('Closed Ticket', 'IPSRS Team', 'Ticket officially closed.');

        return redirect()
            ->back()
            ->with('success', 'Ticket closed successfully.');
    }

    // Workflow Actions: Update Work Performed
    public function updateWorkPerformed(Request $request, Ticket $ticket)
    {
        $request->validate([
            'work_performed' => 'nullable|string|max:4000',
        ]);

        $newEntry = trim((string) $request->work_performed);
        if (!empty($newEntry)) {
            $existingSummary = trim((string) $ticket->work_performed);
            $updatedSummary = $existingSummary ? $existingSummary . "\n" . $newEntry : $newEntry;

            $ticket->update([
                'work_performed' => $updatedSummary,
            ]);

            $performer = auth()->user()?->name ?? 'Technician';

            // 1. Log granular entry to dedicated ticket_work_logs table
            $ticket->workLogs()->create([
                'performed_by' => $performer,
                'content'      => $newEntry,
            ]);

            // 2. Log concise high-level event to main Activity Timeline
            $ticket->logActivity('Work Performed Updated', $performer, 'Recorded new troubleshooting entry.');
        }

        return redirect()
            ->back()
            ->with('success', 'Work performed entry recorded successfully.');
    }

    // Equipment Movement History Page (/equipment-movements)
    public function movements(Request $request)
    {
        $statusFilter = $request->input('status', 'all');
        $search = $request->input('search');

        $baseQuery = Ticket::query()
            ->with(['asset', 'technicians'])
            ->where(function ($q) {
                $q->whereNotNull('sent_to_workshop_date')
                    ->orWhereNotNull('sent_by');
            });

        $allCount = (clone $baseQuery)->count();
        $inWorkshopCount = (clone $baseQuery)->whereNull('returned_date')->count();
        $returnedCount = (clone $baseQuery)->whereNotNull('returned_date')->count();

        $tickets = $baseQuery
            ->when($statusFilter === 'in_workshop', function ($query) {
                $query->whereNull('returned_date');
            })
            ->when($statusFilter === 'returned', function ($query) {
                $query->whereNotNull('returned_date');
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ticket_code', 'like', "%{$search}%")
                        ->orWhere('room', 'like', "%{$search}%")
                        ->orWhere('sent_by', 'like', "%{$search}%")
                        ->orWhere('received_by_workshop', 'like', "%{$search}%")
                        ->orWhere('returned_by', 'like', "%{$search}%")
                        ->orWhere('received_by_user', 'like', "%{$search}%")
                        ->orWhereHas('asset', function ($aq) use ($search) {
                            $aq->where('asset_name', 'like', "%{$search}%")
                                ->orWhere('asset_code', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('sent_to_workshop_date')
            ->paginate(15)
            ->withQueryString();

        return view('tickets.movements', compact('tickets', 'statusFilter', 'search', 'inWorkshopCount', 'returnedCount', 'allCount'));
    }

    // Workflow Actions: Update Equipment Movement & Completeness
    public function updateMovement(Request $request, Ticket $ticket)
    {
        $request->validate([
            'equipment_completeness' => 'nullable|string',
            'sent_to_workshop_date'  => 'nullable|date',
            'sent_by'                => 'nullable|string|max:255',
            'received_by_workshop'   => 'nullable|string|max:255',
            'returned_date'          => 'nullable|date',
            'returned_by'            => 'nullable|string|max:255',
            'received_by_user'       => 'nullable|string|max:255',
        ]);

        $data = [];
        $fields = [
            'equipment_completeness',
            'sent_to_workshop_date',
            'sent_by',
            'received_by_workshop',
            'returned_date',
            'returned_by',
            'received_by_user',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        $ticket->update($data);

        $performer = auth()->user()?->name ?? 'Technician';
        $ticket->logActivity('Updated Equipment Details', $performer, 'Updated equipment movement / completeness records.');

        return redirect()
            ->back()
            ->with('success', 'Equipment details updated successfully.');
    }
}
