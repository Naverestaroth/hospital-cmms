<x-app-layout>

    <div class="space-y-8 max-w-6xl">

        <!-- Top Header & Navigation -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('assets.index') }}" class="text-sm font-semibold text-emerald-600 hover:underline">
                    ← Back to Assets Inventory
                </a>
                <div class="flex items-center gap-3 mt-2">
                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ $asset->asset_name }}
                    </h1>
                    @php
                        $statusClasses = match(strtolower($asset->status)) {
                            'berfungsi', 'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'dalam perbaikan', 'maintenance' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'rusak', 'broken', 'tidak berfungsi' => 'bg-red-50 text-red-700 border-red-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                        };
                    @endphp
                    <span class="rounded-full border px-3.5 py-1 text-xs font-bold capitalize {{ $statusClasses }}">
                        {{ $asset->status }}
                    </span>
                </div>
                <p class="mt-1 text-sm font-medium text-slate-500">
                    Code: <span class="font-mono font-bold text-slate-700">{{ $asset->asset_code }}</span> • Room: <span class="font-semibold text-slate-700">{{ $asset->room ?? 'N/A' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('assets.edit', $asset) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    Edit Equipment
                </a>
            </div>
        </div>

        <!-- Asset Statistics (Bento Summary Cards) -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            
            <!-- Total Tickets -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Total Tickets</span>
                <div class="text-2xl font-bold text-slate-900">{{ $stats['total_tickets'] }}</div>
                <span class="text-[10px] text-blue-600 font-medium">Reactive Requests</span>
            </div>

            <!-- Total Corrective Reports -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Corrective Reports</span>
                <div class="text-2xl font-bold text-slate-900">{{ $stats['total_correctives'] }}</div>
                <span class="text-[10px] text-amber-600 font-medium">Repair Completed</span>
            </div>

            <!-- Total Preventive Maintenance -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Preventive Checks</span>
                <div class="text-2xl font-bold text-slate-900">{{ $stats['total_preventives'] }}</div>
                <span class="text-[10px] text-emerald-600 font-medium">Scheduled Work</span>
            </div>

            <!-- Last Repair Date -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Last Repair</span>
                <div class="text-sm font-bold text-slate-800">
                    {{ $stats['last_repair_date'] ? \Carbon\Carbon::parse($stats['last_repair_date'])->format('d M Y') : '—' }}
                </div>
                <span class="text-[10px] text-slate-400 block">Corrective Date</span>
            </div>

            <!-- Last Preventive Date -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-1">
                <span class="text-xs font-semibold text-slate-400 block uppercase tracking-wider">Last Preventive</span>
                <div class="text-sm font-bold text-slate-800">
                    {{ $stats['last_preventive_date'] ? \Carbon\Carbon::parse($stats['last_preventive_date'])->format('d M Y') : '—' }}
                </div>
                <span class="text-[10px] text-slate-400 block">Scheduled Date</span>
            </div>

        </div>

        <!-- Asset Information Card -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Equipment Specification & Location</h2>
            
            <div class="mt-4 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block">Equipment Name</span>
                    <span class="font-semibold text-slate-800">{{ $asset->asset_name }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Brand</span>
                    <span class="font-semibold text-slate-800">{{ $asset->brand ?? '—' }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Type / Model</span>
                    <span class="font-semibold text-slate-800">{{ $asset->type ?? '—' }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Serial Number</span>
                    <span class="font-mono font-semibold text-slate-800">{{ $asset->serial_number ?? '—' }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Room / Location</span>
                    <span class="font-semibold text-slate-800">{{ $asset->room ?? '—' }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Procurement Year</span>
                    <span class="font-semibold text-slate-800">{{ $asset->procurement_year ?? '—' }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Asset Code</span>
                    <span class="font-mono font-semibold text-slate-800">{{ $asset->asset_code }}</span>
                </div>

                <div>
                    <span class="text-xs text-slate-400 block">Current Status</span>
                    <span class="font-semibold text-slate-800 capitalize">{{ $asset->status }}</span>
                </div>
            </div>

            @if($asset->description)
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400 block mb-1">Equipment Description / Remarks</span>
                    <div class="text-xs text-slate-700 bg-slate-50 border border-slate-200/80 rounded-2xl p-3">
                        {{ $asset->description }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Merged Chronological Asset Timeline -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Unified Asset Activity Timeline</h2>
                    <p class="text-xs text-slate-500">Merged chronological history of Tickets, Corrective Maintenance, and Preventive Maintenance.</p>
                </div>
                <span class="text-xs font-semibold text-slate-500">{{ $timelineEvents->count() }} total activity events</span>
            </div>

            <!-- Scrollable Timeline Container -->
            <div class="max-h-[400px] overflow-y-auto pr-2 space-y-4 scrollbar-thin">
                @forelse($timelineEvents as $event)
                    <div class="relative pl-6 border-l-2 border-slate-200 pb-4 last:pb-0">
                        @php
                            $dotColor = match($event['type']) {
                                'Ticket' => 'bg-blue-600',
                                'Corrective' => 'bg-amber-600',
                                'Preventive' => 'bg-emerald-600',
                                default => 'bg-slate-600',
                            };
                        @endphp
                        <div class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full {{ $dotColor }} ring-4 ring-white"></div>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                            <div class="flex items-center gap-2">
                                <span class="rounded-full border px-2.5 py-0.5 text-[10px] font-bold {{ $event['badge_class'] }}">
                                    {{ $event['type'] }}
                                </span>
                                <a href="{{ $event['url'] }}" class="text-xs font-bold text-slate-900 hover:text-blue-600">
                                    {{ $event['title'] }}
                                </a>
                            </div>
                            <span class="text-[11px] font-medium text-slate-400">{{ $event['date_formatted'] }}</span>
                        </div>

                        <div class="text-xs font-medium text-slate-600 mt-1">{{ $event['subtitle'] }}</div>
                        
                        @if($event['description'])
                            <div class="text-xs text-slate-700 bg-slate-50 border border-slate-100 rounded-xl p-2.5 mt-1.5 leading-relaxed">
                                {{ $event['description'] }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2">
                            <span>Performer / Tech: <strong>{{ $event['performers'] }}</strong></span>
                            <a href="{{ $event['url'] }}" class="text-blue-600 font-semibold hover:underline">View Record →</a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic py-6 text-center">No recorded activity history for this equipment yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Single Section: Maintenance History with Segmented Control Tabs -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6" x-data="{ activeTab: 'tickets' }">
            
            <!-- Segmented Control Tab Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Maintenance History</h2>
                    <p class="text-xs text-slate-500">Categorized equipment records for Tickets, Corrective Maintenance, and Preventive Maintenance.</p>
                </div>

                <!-- Modern Segmented Control Pills -->
                <div class="inline-flex rounded-2xl bg-slate-100 p-1.5 border border-slate-200/80 text-xs font-semibold">
                    
                    <!-- Ticket History Tab -->
                    <button
                        type="button"
                        @click="activeTab = 'tickets'"
                        :class="activeTab === 'tickets' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="rounded-xl px-4 py-2 transition flex items-center gap-2">
                        <span>Ticket History</span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold"
                              :class="activeTab === 'tickets' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-600'">
                            {{ $asset->tickets->count() }}
                        </span>
                    </button>

                    <!-- Corrective Maintenance Tab -->
                    <button
                        type="button"
                        @click="activeTab = 'correctives'"
                        :class="activeTab === 'correctives' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="rounded-xl px-4 py-2 transition flex items-center gap-2">
                        <span>Corrective Maintenance</span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold"
                              :class="activeTab === 'correctives' ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-600'">
                            {{ $asset->correctives->count() }}
                        </span>
                    </button>

                    <!-- Preventive Maintenance Tab -->
                    <button
                        type="button"
                        @click="activeTab = 'preventives'"
                        :class="activeTab === 'preventives' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="rounded-xl px-4 py-2 transition flex items-center gap-2">
                        <span>Preventive Maintenance</span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold"
                              :class="activeTab === 'preventives' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600'">
                            {{ $asset->preventives->count() }}
                        </span>
                    </button>

                </div>
            </div>

            <!-- Tab Content 1: Ticket History -->
            <div x-show="activeTab === 'tickets'" class="space-y-4">
                <div class="overflow-x-auto max-h-[480px] overflow-y-auto scrollbar-thin border border-slate-100 rounded-2xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b sticky top-0 bg-white shadow-xs z-10">
                            <tr>
                                <th class="px-4 py-3 text-left">Ticket Code</th>
                                <th class="px-4 py-3 text-left">Created Date</th>
                                <th class="px-4 py-3 text-left">Priority</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Reported By</th>
                                <th class="px-4 py-3 text-left">Assigned Technician(s)</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($asset->tickets as $ticket)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $ticket->ticket_code }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium {{ $ticket->priority === 'High' ? 'text-red-600' : 'text-slate-700' }}">{{ $ticket->priority }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[10px] font-bold text-slate-700">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $ticket->reported_by }}</td>
                                    <td class="px-4 py-3">
                                        @if($ticket->technicians->count() > 0)
                                            <span class="font-medium text-slate-800">{{ $ticket->technicians->pluck('name')->implode(', ') }}</span>
                                        @else
                                            <span class="text-slate-400 italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="font-bold text-blue-600 hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-slate-400">
                                        <div class="space-y-1">
                                            <p class="font-bold text-slate-700 text-sm">No Ticket History</p>
                                            <p class="text-xs text-slate-400">No reactive tickets recorded for this asset.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content 2: Corrective Maintenance -->
            <div x-show="activeTab === 'correctives'" x-cloak class="space-y-4">
                <div class="overflow-x-auto max-h-[480px] overflow-y-auto scrollbar-thin border border-slate-100 rounded-2xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b sticky top-0 bg-white shadow-xs z-10">
                            <tr>
                                <th class="px-4 py-3 text-left">Repair Date</th>
                                <th class="px-4 py-3 text-left">Problem Symptoms</th>
                                <th class="px-4 py-3 text-left">Action Solution</th>
                                <th class="px-4 py-3 text-left">Sparepart Used</th>
                                <th class="px-4 py-3 text-left">Technician</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($asset->correctives as $corrective)
                                @php
                                    $techStr = is_array($corrective->technician) ? implode(', ', $corrective->technician) : ($corrective->technician ?: '—');
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $corrective->repair_date ? \Carbon\Carbon::parse($corrective->repair_date)->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ Str::limit($corrective->problem, 40) ?: '—' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ Str::limit($corrective->solution, 40) ?: '—' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $corrective->sparepart ?: 'None' }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $techStr }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('correctives.show', $corrective) }}" class="font-bold text-amber-700 hover:underline">View Report</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-400">
                                        <div class="space-y-1">
                                            <p class="font-bold text-slate-700 text-sm">No Corrective Maintenance History</p>
                                            <p class="text-xs text-slate-400">No corrective repair reports recorded for this asset.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content 3: Preventive Maintenance -->
            <div x-show="activeTab === 'preventives'" x-cloak class="space-y-4">
                <div class="overflow-x-auto max-h-[480px] overflow-y-auto scrollbar-thin border border-slate-100 rounded-2xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b sticky top-0 bg-white shadow-xs z-10">
                            <tr>
                                <th class="px-4 py-3 text-left">Schedule Date</th>
                                <th class="px-4 py-3 text-left">Condition / Type</th>
                                <th class="px-4 py-3 text-left">Technician</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($asset->preventives as $preventive)
                                @php
                                    $techStr = is_array($preventive->technician) ? implode(', ', $preventive->technician) : ($preventive->technician ?: '—');
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $preventive->schedule_date ? \Carbon\Carbon::parse($preventive->schedule_date)->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $preventive->condition ?: 'Routine Inspection' }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $techStr }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-800">
                                            {{ $preventive->status ?: 'Completed' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('preventives.show', $preventive) }}" class="font-bold text-emerald-700 hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-400">
                                        <div class="space-y-1">
                                            <p class="font-bold text-slate-700 text-sm">No Preventive Maintenance History</p>
                                            <p class="text-xs text-slate-400">No scheduled preventive maintenance records for this asset.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Technical Documents Section -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-lg font-bold text-slate-900">Technical Documents</h2>
                <span class="text-xs font-semibold text-slate-500">{{ $asset->documents->count() }} document(s)</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($asset->documents as $document)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $document->title }}</p>
                            <p class="text-[11px] text-slate-400">{{ $document->document_type }}</p>
                        </div>
                        <a href="{{ route('documents.view', $document) }}" class="text-xs font-bold text-emerald-600 hover:underline">View Document</a>
                    </div>
                @empty
                    <p class="py-6 text-center text-xs text-slate-400">No technical documents uploaded for this equipment.</p>
                @endforelse
            </div>
        </div>

    </div>

</x-app-layout>