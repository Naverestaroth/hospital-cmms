<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\Preventive;
use App\Models\Corrective;
use App\Models\Sparepart;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index', [

            'assetCount' => Asset::count(),

            'ticketCount' => Ticket::count(),

            'preventiveCount' => Preventive::count(),

            'correctiveCount' => Corrective::count(),

            'sparepartCount' => Sparepart::count(),

            'vendorCount' => Vendor::count(),

        ]);
    }

    public function exportPdf()
    {
        $assets = Asset::all();

        $pdf = Pdf::loadView('reports.assets-pdf', compact('assets'));

        return $pdf->download('asset-report.pdf');
    }
}
