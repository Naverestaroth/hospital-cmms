<?php

namespace App\Http\Controllers;

use App\Models\Preventive;
use App\Models\Corrective;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()

    {

        $preventives = Preventive::with('asset')->get();

        $correctives = Corrective::with('ticket.asset')->get();

        return view('history.index', compact(

            'preventives',

            'correctives'

        ));
    }
}
