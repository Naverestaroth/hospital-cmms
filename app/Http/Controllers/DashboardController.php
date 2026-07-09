<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\Preventive;
use App\Models\Corrective;
use App\Models\Sparepart;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [

            'assetCount' => Asset::count(),

            'ticketCount' => Ticket::count(),

            'preventiveCount' => Preventive::count(),

            'correctiveCount' => Corrective::count(),

            'sparepartCount' => Sparepart::count(),

            'vendorCount' => Vendor::count(),

            'openTicket' => Ticket::where('status', 'Open')->count(),

            'progressTicket' => Ticket::where('status', 'In Progress')->count(),

            'completedTicket' => Ticket::where('status', 'Completed')->count(),

        ]);
    }
}
