<?php

namespace App\Http\Controllers;

use App\Models\Corrective;
use App\Models\Ticket;
use Illuminate\Http\Request;

class CorrectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $correctives = Corrective::with('ticket.asset')->get();
        return view('correctives.index', compact('correctives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tickets = Ticket::all();
        return view('correctives.create', compact('tickets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'ticket_id' => 'required',

            'technician' => 'required',

            'repair_date' => 'required',

            'status' => 'required',

            'notes' => 'nullable'

        ]);

        Corrective::create($request->all());

        return redirect()

            ->route('correctives.index')

            ->with('success', 'Corrective maintenance added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Corrective $corrective)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Corrective $corrective)
    {
        $tickets = Ticket::all();

        return view(

            'correctives.edit',

            compact('corrective', 'tickets')

        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Corrective $corrective)
    {
        $request->validate([

            'ticket_id' => 'required',

            'technician' => 'required',

            'repair_date' => 'required',

            'status' => 'required',

            'notes' => 'nullable'

        ]);

        $corrective->update($request->all());

        return redirect()

            ->route('correctives.index')

            ->with('success', 'Corrective updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corrective $corrective)
    {
        $corrective->delete();

        return redirect()

            ->route('correctives.index')

            ->with('success', 'Corrective deleted.');
    }
}
