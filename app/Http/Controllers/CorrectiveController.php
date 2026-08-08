<?php

namespace App\Http\Controllers;

use App\Models\Corrective;
use App\Models\Asset;
use App\Models\Ticket;
use Illuminate\Http\Request;

class CorrectiveController extends Controller
{
    public function index(Request $request)
    {
        $sortableColumns = ['repair_date', 'response_time', 'room', 'asset_name', 'brand', 'type', 'serial_number'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns) && $sortField !== 'created_at') {
            $sortField = 'created_at';
        }

        $correctives = Corrective::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('room', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('technician', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        return view('correctives.index', compact('correctives', 'sortField', 'sortDirection'));
    }

    public function create(Request $request)
    {
        $ticket = null;
        if ($request->filled('ticket_id')) {
            $ticket = Ticket::with(['asset', 'technicians', 'corrective'])->find($request->ticket_id);
            if ($ticket && $ticket->corrective) {
                return redirect()
                    ->route('correctives.show', $ticket->corrective)
                    ->with('info', "A Corrective Report already exists for Ticket {$ticket->ticket_code}.");
            }
        }

        return view('correctives.create', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id'         => 'nullable|exists:tickets,id',
            'repair_date'       => 'required|date',
            'jam_laporan'       => 'nullable',
            'jam_visit'         => 'nullable',
            'response_time'     => 'nullable|string|max:100',
            'room'              => 'nullable|string|max:255',
            'asset_code'        => 'nullable|string|max:255',
            'asset_name'        => 'nullable|string|max:255',
            'brand'             => 'nullable|string|max:255',
            'type'              => 'nullable|string|max:255',
            'serial_number'     => 'nullable|string|max:255',
            'tanggal_instal'    => 'nullable',
            'distributor'       => 'nullable',
            'service_type'      => 'nullable|array',
            'inspection'        => 'nullable|array',
            'problem'           => 'nullable|string',
            'solution'          => 'nullable|string',
            'sparepart'         => 'nullable|string|max:255',
            'quantity'          => 'nullable|integer',
            'inspection_result' => 'nullable|string|max:255',
            'technician'        => 'nullable|array',
            'user_name'         => 'nullable|string|max:255',
            'position'          => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        if (!empty($validated['ticket_id'])) {
            $existing = Corrective::where('ticket_id', $validated['ticket_id'])->first();
            if ($existing) {
                return redirect()
                    ->route('correctives.show', $existing)
                    ->with('info', 'A Corrective Report already exists for this ticket.');
            }
        }

        $payload = $validated;
        unset($payload['jam_laporan']);
        unset($payload['jam_visit']);
        unset($payload['distributor']);

        if (!empty($payload['tanggal_instal'])) {
            $val = trim((string) $payload['tanggal_instal']);
            if (preg_match('/^\d{4}$/', $val)) {
                $payload['tanggal_instal'] = $val . '-01-01';
            }
        }

        $corrective = Corrective::create($payload);

        // If created from a ticket, update ticket status to Closed & log timeline entry
        if (!empty($corrective->ticket_id) && ($ticket = Ticket::find($corrective->ticket_id))) {
            $ticket->update(['status' => 'Closed']);
            
            $performer = is_array($corrective->technician) ? implode(', ', $corrective->technician) : ($corrective->technician ?: 'IPSRS Technician');
            $ticket->logActivity('Corrective Report Completed', $performer, 'Corrective maintenance report submitted & ticket closed.');
        }

        return redirect()
            ->route('correctives.show', $corrective)
            ->with('success', 'Corrective maintenance report created successfully.');
    }

    public function show(Corrective $corrective)
    {
        $corrective->load('ticket');
        return view('correctives.show', compact('corrective'));
    }

    public function edit(Corrective $corrective)
    {
        $rooms = Asset::query()
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        if ($corrective->room && !$rooms->contains($corrective->room)) {
            $rooms->push($corrective->room);
        }

        return view('correctives.edit', compact('corrective', 'rooms'));
    }

    public function update(Request $request, Corrective $corrective)
    {
        $validated = $request->validate([
            'ticket_id'         => 'nullable|exists:tickets,id',
            'repair_date'       => 'required|date',
            'jam_laporan'       => 'nullable',
            'jam_visit'         => 'nullable',
            'response_time'     => 'nullable|string|max:100',
            'room'              => 'nullable|string|max:255',
            'asset_code'        => 'nullable|string|max:255',
            'asset_name'        => 'nullable|string|max:255',
            'brand'             => 'nullable|string|max:255',
            'type'              => 'nullable|string|max:255',
            'serial_number'     => 'nullable|string|max:255',
            'tanggal_instal'    => 'nullable',
            'distributor'       => 'nullable',
            'service_type'      => 'nullable|array',
            'inspection'        => 'nullable|array',
            'problem'           => 'nullable|string',
            'solution'          => 'nullable|string',
            'sparepart'         => 'nullable|string|max:255',
            'quantity'          => 'nullable|integer',
            'inspection_result' => 'nullable|string|max:255',
            'technician'        => 'nullable|array',
            'user_name'         => 'nullable|string|max:255',
            'position'          => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        $payload = $validated;
        if (!empty($payload['tanggal_instal'])) {
            $val = trim((string) $payload['tanggal_instal']);
            if (preg_match('/^\d{4}$/', $val)) {
                $payload['tanggal_instal'] = $val . '-01-01';
            }
        }

        $corrective->update($payload);

        return redirect()
            ->route('correctives.show', $corrective)
            ->with('success', 'Corrective maintenance report updated successfully.');
    }

    public function destroy(Corrective $corrective)
    {
        $corrective->delete();

        return redirect()
            ->route('correctives.index')
            ->with('success', 'Corrective maintenance report deleted successfully.');
    }
}
