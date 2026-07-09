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
    public function index()
    {
        $preventives = Preventive::with('asset')->get();
        return view('preventives.index', compact('preventives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::all();
        return view('preventives.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Preventive $preventive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Preventive $preventive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Preventive $preventive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preventive $preventive)
    {
        //
    }
}
