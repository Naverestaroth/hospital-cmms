<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\Preventive;
use App\Models\Corrective;
use App\Models\Sparepart;
use App\Models\Vendor;
use App\Models\Technician;
use App\Models\TicketActivity;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTicket = Ticket::count();
        $openTicket = Ticket::where('status', 'Open')->count();
        $progressTicket = Ticket::where('status', 'In Progress')->count();
        $completedTicket = Ticket::whereIn('status', ['Completed', 'Closed'])->count();
        $activeOpenTicketsCount = Ticket::whereNotIn('status', ['Closed', 'Rejected', 'Cancelled'])->count();

        $recentTickets = Ticket::with('asset')
            ->latest()
            ->take(5)
            ->get();

        $totalAssetCount = Asset::count();

        // 1. Categorize all assets in DB into 5 status categories for Donut Chart
        $allAssets = Asset::query()->select('status')->get();

        $countBerfungsi = 0;
        $countDalamPerbaikan = 0;
        $countTidakBerfungsi = 0;
        $countProsesPenghapusan = 0;
        $countOther = 0;

        foreach ($allAssets as $asset) {
            $st = strtolower(trim((string) $asset->status));
            if (in_array($st, ['berfungsi', 'active'])) {
                $countBerfungsi++;
            } elseif (in_array($st, ['dalam perbaikan', 'maintenance'])) {
                $countDalamPerbaikan++;
            } elseif (in_array($st, ['rusak', 'broken', 'tidak berfungsi'])) {
                $countTidakBerfungsi++;
            } elseif (in_array($st, ['proses penghapusan'])) {
                $countProsesPenghapusan++;
            } else {
                $countOther++;
            }
        }

        $assetStatusData = [
            [
                'key' => 'berfungsi',
                'label' => 'Berfungsi',
                'count' => $countBerfungsi,
                'percentage' => $totalAssetCount > 0 ? round(($countBerfungsi / $totalAssetCount) * 100, 1) : 0,
                'color' => '#22C55E',
                'badge_bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'indicator' => 'bg-emerald-500',
            ],
            [
                'key' => 'dalam perbaikan',
                'label' => 'Dalam Perbaikan',
                'count' => $countDalamPerbaikan,
                'percentage' => $totalAssetCount > 0 ? round(($countDalamPerbaikan / $totalAssetCount) * 100, 1) : 0,
                'color' => '#F59E0B',
                'badge_bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                'indicator' => 'bg-amber-500',
            ],
            [
                'key' => 'rusak',
                'label' => 'Tidak Berfungsi',
                'count' => $countTidakBerfungsi,
                'percentage' => $totalAssetCount > 0 ? round(($countTidakBerfungsi / $totalAssetCount) * 100, 1) : 0,
                'color' => '#EF4444',
                'badge_bg' => 'bg-red-50 text-red-700 border-red-200',
                'indicator' => 'bg-red-500',
            ],
            [
                'key' => 'proses penghapusan',
                'label' => 'Proses Penghapusan',
                'count' => $countProsesPenghapusan,
                'percentage' => $totalAssetCount > 0 ? round(($countProsesPenghapusan / $totalAssetCount) * 100, 1) : 0,
                'color' => '#6B7280',
                'badge_bg' => 'bg-slate-100 text-slate-700 border-slate-200',
                'indicator' => 'bg-slate-500',
            ],
            [
                'key' => 'other',
                'label' => 'Other / Custom',
                'count' => $countOther,
                'percentage' => $totalAssetCount > 0 ? round(($countOther / $totalAssetCount) * 100, 1) : 0,
                'color' => '#8B5CF6',
                'badge_bg' => 'bg-purple-50 text-purple-700 border-purple-200',
                'indicator' => 'bg-purple-500',
            ],
        ];

        // 2. Raw asset statuses audit
        $rawStatusRows = Asset::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();

        $uniqueStatusCount = $rawStatusRows->count();
        $rawAssetStatusData = $rawStatusRows->map(function ($row) use ($totalAssetCount) {
            return [
                'status' => $row->status ?? '(Empty)',
                'count' => (int) $row->total,
                'percentage' => $totalAssetCount > 0 ? round(($row->total / $totalAssetCount) * 100, 2) : 0,
            ];
        })->values()->toArray();

        // 3. Monthly Counts
        $thisMonthCorrective = Corrective::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $thisMonthPreventive = Preventive::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        // 4. Ticket Workflow Status Breakdown
        $ticketWorkflowStatuses = [
            'Waiting Approval',
            'Open',
            'Assigned',
            'Accepted',
            'In Progress',
            'Waiting Sparepart',
            'Waiting Vendor',
            'Waiting User',
            'Repair Completed',
            'Closed',
        ];

        $rawTicketCounts = Ticket::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $workflowBreakdown = [];
        foreach ($ticketWorkflowStatuses as $st) {
            $count = $rawTicketCounts[$st] ?? 0;
            $pct = $totalTicket > 0 ? round(($count / $totalTicket) * 100, 1) : 0;
            $workflowBreakdown[] = [
                'status' => $st,
                'count' => $count,
                'percentage' => $pct,
            ];
        }

        // 5. Technician Active Workload
        $techniciansWorkload = Technician::query()
            ->withCount(['tickets' => function ($q) {
                $q->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled']);
            }])
            ->orderBy('tickets_count', 'desc')
            ->get();

        $maxActiveTickets = max(1, $techniciansWorkload->max('tickets_count') ?? 1);

        $totalTechs = Technician::count();
        $busyTechsCount = Technician::whereHas('tickets', function ($q) {
            $q->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled']);
        })->count();
        $availableTechsCount = max(0, $totalTechs - $busyTechsCount);
        $technicianAvailabilityPct = $totalTechs > 0 ? round(($availableTechsCount / $totalTechs) * 100) : 100;

        // 6. Recent Ticket Activities
        $recentActivities = TicketActivity::with('ticket')->latest()->take(10)->get();

        // 7. Upcoming Preventive Maintenance Schedules
        $upcomingPreventives = Preventive::query()
            ->orderBy('schedule_date', 'asc')
            ->take(6)
            ->get();

        // 8. Available Months for Card 4 Analytics Component
        $currentDate = now();
        $m1 = (clone $currentDate)->subMonth();
        $m2 = clone $currentDate;
        $m3 = (clone $currentDate)->addMonth();

        $availableMonths = [
            [
                'month' => (int) $m1->format('m'),
                'year' => (int) $m1->format('Y'),
                'name' => $m1->format('F'),
                'is_current' => false,
            ],
            [
                'month' => (int) $m2->format('m'),
                'year' => (int) $m2->format('Y'),
                'name' => $m2->format('F'),
                'is_current' => true,
            ],
            [
                'month' => (int) $m3->format('m'),
                'year' => (int) $m3->format('Y'),
                'name' => $m3->format('F'),
                'is_current' => false,
            ],
        ];

        $initialAnalyticsData = $this->getTicketAnalyticsData((int) $m2->format('m'), (int) $m2->format('Y'));

        // Equipment Status KPI Calculations for CARD 1 (Operations-First)
        $equipmentAvailabilityPct = $totalAssetCount > 0 ? round(($countBerfungsi / $totalAssetCount) * 100, 1) : 100.0;
        $devicesDown = $countDalamPerbaikan + $countTidakBerfungsi;
        $countDisposal = $countProsesPenghapusan;

        // Delta from yesterday: tickets created since yesterday as proxy for new failures
        $newTicketsSinceYesterday = Ticket::where('created_at', '>=', now()->subDay())
            ->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled'])
            ->count();

        // Weekly peak: max non-operational assets this week (approximate via tickets created this week)
        $ticketsThisWeek = Ticket::where('created_at', '>=', now()->startOfWeek())
            ->count();

        // Monthly average tickets per day (as context)
        $ticketsThisMonth = Ticket::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $daysElapsed = max(1, now()->day);
        $monthlyAvgPerDay = round($ticketsThisMonth / $daysElapsed, 1);

        // Dashed track dot positions (normalized to 0-100% scale relative to total assets)
        $weeklyPeakPct = $totalAssetCount > 0 ? min(100, round(($ticketsThisWeek / max(1, $totalAssetCount)) * 100 * 10)) : 50;
        $monthlyAvgPct = $totalAssetCount > 0 ? min(100, round(($monthlyAvgPerDay / max(1, $totalAssetCount)) * 100 * 10)) : 30;

        // Operational status message (action-oriented, not grade-oriented)
        if ($devicesDown === 0) {
            $statusIcon = 'check';
            $statusColor = 'bg-[#E8F6EF] text-[#2E9E6D]';
            $statusHeadline = 'No escalation needed';
            $statusDetail = 'All ' . $totalAssetCount . ' hospital assets are fully operational.';
        } elseif ($newTicketsSinceYesterday === 0 && $devicesDown <= 10) {
            $statusIcon = 'check';
            $statusColor = 'bg-[#E8F6EF] text-[#2E9E6D]';
            $statusHeadline = 'No escalation needed';
            $statusDetail = $countDalamPerbaikan . ' in repair (stable) · ' . $countTidakBerfungsi . ' non-operational';
        } elseif ($newTicketsSinceYesterday >= 5 || $devicesDown >= 20) {
            $statusIcon = 'alert';
            $statusColor = 'bg-[#FEEBEB] text-[#E2574C]';
            $statusHeadline = $newTicketsSinceYesterday . ' new failures since yesterday';
            $statusDetail = 'Recommend reviewing assignment queue and calling in additional staff.';
        } else {
            $statusIcon = 'warning';
            $statusColor = 'bg-[#FFF6E5] text-[#DB9A34]';
            $statusHeadline = $newTicketsSinceYesterday . ' new since yesterday';
            $statusDetail = $countDalamPerbaikan . ' in repair · ' . $countTidakBerfungsi . ' non-operational · Monitor closely';
        }

        return view('dashboard', [
            'assetCount' => $totalAssetCount,
            'totalAssetCount' => $totalAssetCount,
            'equipmentAvailabilityPct' => $equipmentAvailabilityPct,
            'devicesDown' => $devicesDown,
            'newTicketsSinceYesterday' => $newTicketsSinceYesterday,
            'ticketsThisWeek' => $ticketsThisWeek,
            'monthlyAvgPerDay' => $monthlyAvgPerDay,
            'weeklyPeakPct' => $weeklyPeakPct,
            'monthlyAvgPct' => $monthlyAvgPct,
            'statusIcon' => $statusIcon,
            'statusColor' => $statusColor,
            'statusHeadline' => $statusHeadline,
            'statusDetail' => $statusDetail,
            'countBerfungsi' => $countBerfungsi,
            'countDalamPerbaikan' => $countDalamPerbaikan,
            'countTidakBerfungsi' => $countTidakBerfungsi,
            'countProsesPenghapusan' => $countProsesPenghapusan,
            'countDisposal' => $countDisposal,
            'countOther' => $countOther,
            'assetStatusData' => $assetStatusData,
            'rawAssetStatusData' => $rawAssetStatusData,
            'uniqueStatusCount' => $uniqueStatusCount,
            'ticketCount' => $totalTicket,
            'totalTicket' => $totalTicket,
            'openTicket' => $openTicket,
            'activeOpenTicketsCount' => $activeOpenTicketsCount,
            'progressTicket' => $progressTicket,
            'recentTickets' => $recentTickets,
            'completedTicket' => $completedTicket,
            'maintenanceCount' => Preventive::count() + Corrective::count(),
            'preventiveCount' => Preventive::count(),
            'correctiveCount' => Corrective::count(),
            'thisMonthCorrective' => $thisMonthCorrective,
            'thisMonthPreventive' => $thisMonthPreventive,
            'sparepartCount' => Sparepart::count(),
            'vendorCount' => Vendor::count(),
            'workflowBreakdown' => $workflowBreakdown,
            'techniciansWorkload' => $techniciansWorkload,
            'maxActiveTickets' => $maxActiveTickets,
            'totalTechs' => $totalTechs,
            'availableTechsCount' => $availableTechsCount,
            'technicianAvailabilityPct' => $technicianAvailabilityPct,
            'recentActivities' => $recentActivities,
            'upcomingPreventives' => $upcomingPreventives,
            'availableMonths' => $availableMonths,
            'initialAnalyticsData' => $initialAnalyticsData,
        ]);
    }

    public function ticketAnalytics(\Illuminate\Http\Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        return response()->json($this->getTicketAnalyticsData($month, $year));
    }

    private function getTicketAnalyticsData(int $month, int $year): array
    {
        $monthTickets = Ticket::query()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $totalRequests = $monthTickets->count();
        $openTickets = $monthTickets->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled'])->count();
        $closedTickets = $monthTickets->where('status', 'Closed')->count();
        $activeTickets = $totalRequests - $closedTickets;

        $daysInMonth = (int) date('t', strtotime("{$year}-{$month}-01"));
        $bucketsCount = 10;
        $daysPerBucket = (int) ceil($daysInMonth / $bucketsCount);

        $histogram = [];
        $maxCreatedInBucket = 0;

        for ($b = 0; $b < $bucketsCount; $b++) {
            $startDay = ($b * $daysPerBucket) + 1;
            $endDay = min($daysInMonth, ($b + 1) * $daysPerBucket);

            if ($startDay > $daysInMonth) break;

            $startDateStr = sprintf('%04d-%02d-%02d', $year, $month, $startDay);
            $endDateStr = sprintf('%04d-%02d-%02d', $year, $month, $endDay);

            $periodTickets = $monthTickets->filter(function ($t) use ($startDay, $endDay) {
                $day = (int) $t->created_at->format('j');
                return $day >= $startDay && $day <= $endDay;
            });

            $createdCount = $periodTickets->count();
            $openCount = $periodTickets->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled'])->count();
            $closedCount = $periodTickets->where('status', 'Closed')->count();

            if ($createdCount > $maxCreatedInBucket) {
                $maxCreatedInBucket = $createdCount;
            }

            $histogram[] = [
                'period_label' => "Days {$startDay}–{$endDay}",
                'start_date' => $startDateStr,
                'end_date' => $endDateStr,
                'created_count' => $createdCount,
                'open_count' => $openCount,
                'closed_count' => $closedCount,
                'drill_url' => route('tickets.index', [
                    'month' => $month,
                    'year' => $year,
                    'created_from' => $startDateStr,
                    'created_to' => $endDateStr,
                ]),
            ];
        }

        foreach ($histogram as &$bar) {
            if ($maxCreatedInBucket > 0 && $bar['created_count'] > 0) {
                $rawPct = round(($bar['created_count'] / $maxCreatedInBucket) * 100);
                $bar['height_pct'] = max(20, $rawPct);
            } else {
                $bar['height_pct'] = 12;
            }
        }

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F');

        return [
            'year' => $year,
            'month' => $month,
            'month_name' => $monthName,
            'open_tickets' => $openTickets,
            'total_requests' => $totalRequests,
            'active_tickets' => $activeTickets,
            'closed_tickets' => $closedTickets,
            'histogram' => $histogram,
        ];
    }
}
