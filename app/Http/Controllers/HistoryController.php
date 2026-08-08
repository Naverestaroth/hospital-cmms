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
            ->latest()
            ->paginate(15, ['*'], 'tickets_page')
            ->withQueryString();

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
            ->latest()
            ->paginate(15, ['*'], 'correctives_page')
            ->withQueryString();

        $preventives = Preventive::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('technician', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15, ['*'], 'preventives_page')
            ->withQueryString();

        return view('history.index', compact('tickets', 'correctives', 'preventives', 'search'));
    }
}
