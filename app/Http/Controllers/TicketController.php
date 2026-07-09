<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('asset')->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::all();
        return view('tickets.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'asset_id' => 'required',

            'reported_by' => 'required|max:100',

            'issue' => 'required',

            'priority' => 'required',

        ]);

        Ticket::create([

            'ticket_code' => 'TCK-' . rand(1000, 9999),

            'asset_id' => $request->asset_id,

            'reported_by' => $request->reported_by,

            'issue' => $request->issue,

            'priority' => $request->priority,

            'status' => 'Open',

        ]);

        return redirect()

            ->route('tickets.index')

            ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('asset');
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $assets = Asset::all();
        return view('tickets.edit', compact('ticket', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([

            'asset_id' => 'required',
            'reported_by' => 'required',
            'issue' => 'required',
            'priority' => 'required',
            'status' => 'required',

        ]);

        $ticket->update($request->all());
        return redirect()

            ->route('tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
