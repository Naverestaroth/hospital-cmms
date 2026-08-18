<?php

namespace App\Http\Controllers;

use App\Models\Preventive;
use App\Models\Corrective;
use App\Models\Ticket;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // --- Tickets sorting ---
        $ticketsSortable  = ['ticket_code', 'reported_by', 'priority', 'status', 'created_at'];
        $ticketsSort      = in_array($request->input('tickets_sort'), $ticketsSortable)
                            ? $request->input('tickets_sort') : 'created_at';
        $ticketsDir       = $request->input('tickets_dir') === 'asc' ? 'asc' : 'desc';

        $tickets = Ticket::query()
            ->with(['asset', 'technicians'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ticket_code', 'like', "%{$search}%")
                        ->orWhere('reported_by', 'like', "%{$search}%")
                        ->orWhere('issue', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->orderBy($ticketsSort, $ticketsDir)
            ->paginate(15, ['*'], 'tickets_page')
            ->withQueryString();

        // --- Correctives sorting ---
        $correctivesSortable = ['repair_date', 'asset_name', 'room', 'problem', 'solution', 'created_at'];
        $correctivesSort     = in_array($request->input('correctives_sort'), $correctivesSortable)
                               ? $request->input('correctives_sort') : 'created_at';
        $correctivesDir      = $request->input('correctives_dir') === 'asc' ? 'asc' : 'desc';

        $correctives = Corrective::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('problem', 'like', "%{$search}%")
                        ->orWhere('solution', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('technician', 'like', "%{$search}%");
                });
            })
            ->orderBy($correctivesSort, $correctivesDir)
            ->paginate(15, ['*'], 'correctives_page')
            ->withQueryString();

        // --- Preventives sorting ---
        $preventivesSortable = ['schedule_date', 'asset_name', 'room', 'status', 'created_at'];
        $preventivesSort     = in_array($request->input('preventives_sort'), $preventivesSortable)
                               ? $request->input('preventives_sort') : 'created_at';
        $preventivesDir      = $request->input('preventives_dir') === 'asc' ? 'asc' : 'desc';

        $preventives = Preventive::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('technician', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->orderBy($preventivesSort, $preventivesDir)
            ->paginate(15, ['*'], 'preventives_page')
            ->withQueryString();

        return view('history.index', compact(
            'tickets', 'correctives', 'preventives', 'search',
            'ticketsSort', 'ticketsDir',
            'correctivesSort', 'correctivesDir',
            'preventivesSort', 'preventivesDir'
        ));
    }
}
