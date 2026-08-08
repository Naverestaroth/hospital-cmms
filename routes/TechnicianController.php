<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('technicians.index');
    }

    public function show($id)
    {
        // The $id is a placeholder for now. We pass a dummy name for the title.
        return view('technicians.show', ['name' => 'Andi Pratama']);
    }
}