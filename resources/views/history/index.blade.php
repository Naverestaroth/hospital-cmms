<x-app-layout>

    <div class="space-y-6 max-w-6xl">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Maintenance History
                </h1>
                <p class="mt-1 text-slate-500 text-sm">
                    Comprehensive hospital equipment history log covering Tickets, Corrective Reports, and Preventive Schedules.
                </p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <form action="{{ route('history') }}" method="GET" class="flex gap-3">
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search history by asset, ticket code, technician, or status..."
                        class="w-full rounded-2xl border border-slate-200 pl-10 pr-4 py-3 text-sm focus:border-emerald-500 focus:outline-none"
                        value="{{ request('search') }}">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                    Search
                </button>

                @if(request('search'))
                    <a href="{{ route('history') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Single Card: Maintenance History with Segmented Control Tabs -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6" x-data="{ activeTab: 'tickets' }">
            
            <!-- Segmented Control Tab Bar Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Hospital Maintenance Log</h2>
                    <p class="text-xs text-slate-500">Select a maintenance category tab below to view history records.</p>
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
                            {{ $tickets->total() }}
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
                            {{ $correctives->total() }}
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
                            {{ $preventives->total() }}
                        </span>
                    </button>

                </div>
            </div>

            <!-- Tab Content 1: Ticket History Table -->
            <div x-show="activeTab === 'tickets'" class="space-y-4">
                @if($tickets->count() > 0)
                    <div class="overflow-x-auto max-h-[520px] overflow-y-auto scrollbar-thin border border-slate-100 rounded-2xl">
                        <table class="min-w-full text-xs">
                            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b sticky top-0 bg-white shadow-xs z-10">
                                <tr>
                                    <th class="px-4 py-3 text-left">Ticket Code</th>
                                    <th class="px-4 py-3 text-left">Asset</th>
                                    <th class="px-4 py-3 text-left">Room</th>
                                    <th class="px-4 py-3 text-left">Created Date</th>
                                    <th class="px-4 py-3 text-left">Priority</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Technician(s)</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($tickets as $ticket)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $ticket->ticket_code }}</td>
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $ticket->asset?->asset_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $ticket->room ?? $ticket->asset?->room ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-medium {{ $ticket->priority === 'High' ? 'text-red-600' : 'text-slate-700' }}">{{ $ticket->priority }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[10px] font-bold text-slate-700">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($ticket->technicians->count() > 0)
                                                <span class="font-medium text-slate-800">{{ $ticket->technicians->pluck('name')->implode(', ') }}</span>
                                            @else
                                                <span class="text-slate-400 italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="font-bold text-blue-600 hover:underline">View Ticket</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($tickets->hasPages())
                        <div class="pt-2">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-10 text-center space-y-2">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">No Ticket History</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            No ticket records found in maintenance history.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tab Content 2: Corrective Maintenance Table -->
            <div x-show="activeTab === 'correctives'" x-cloak class="space-y-4">
                @if($correctives->count() > 0)
                    <div class="overflow-x-auto max-h-[520px] overflow-y-auto scrollbar-thin border border-slate-100 rounded-2xl">
                        <table class="min-w-full text-xs">
                            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b sticky top-0 bg-white shadow-xs z-10">
                                <tr>
                                    <th class="px-4 py-3 text-left">Repair Date</th>
                                    <th class="px-4 py-3 text-left">Asset</th>
                                    <th class="px-4 py-3 text-left">Room</th>
                                    <th class="px-4 py-3 text-left">Problem Symptoms</th>
                                    <th class="px-4 py-3 text-left">Action Solution</th>
                                    <th class="px-4 py-3 text-left">Technician</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($correctives as $corrective)
                                    @php
                                        $techStr = is_array($corrective->technician) ? implode(', ', array_filter($corrective->technician)) : ($corrective->technician ?: '—');
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-4 py-3 font-semibold text-slate-900">
                                            {{ $corrective->repair_date ? \Carbon\Carbon::parse($corrective->repair_date)->format('d M Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $corrective->asset_name }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $corrective->room ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ Str::limit($corrective->problem, 40) ?: '—' }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ Str::limit($corrective->solution, 40) ?: '—' }}</td>
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $techStr }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('correctives.show', $corrective) }}" class="font-bold text-amber-700 hover:underline">View Report</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($correctives->hasPages())
                        <div class="pt-2">
                            {{ $correctives->links() }}
                        </div>
                    @endif
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-10 text-center space-y-2">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-700 mb-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">No Corrective Maintenance History</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            No corrective repair reports recorded in history.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tab Content 3: Preventive Maintenance Table -->
            <div x-show="activeTab === 'preventives'" x-cloak class="space-y-4">
                @if($preventives->count() > 0)
                    <div class="overflow-x-auto max-h-[520px] overflow-y-auto scrollbar-thin border border-slate-100 rounded-2xl">
                        <table class="min-w-full text-xs">
                            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b sticky top-0 bg-white shadow-xs z-10">
                                <tr>
                                    <th class="px-4 py-3 text-left">Schedule Date</th>
                                    <th class="px-4 py-3 text-left">Asset</th>
                                    <th class="px-4 py-3 text-left">Room</th>
                                    <th class="px-4 py-3 text-left">Condition / Type</th>
                                    <th class="px-4 py-3 text-left">Technician</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($preventives as $preventive)
                                    @php
                                        $techStr = is_array($preventive->technician) ? implode(', ', array_filter($preventive->technician)) : ($preventive->technician ?: '—');
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-4 py-3 font-semibold text-slate-900">
                                            {{ $preventive->schedule_date ? \Carbon\Carbon::parse($preventive->schedule_date)->format('d M Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $preventive->asset_name }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $preventive->room ?? 'N/A' }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($preventives->hasPages())
                        <div class="pt-2">
                            {{ $preventives->links() }}
                        </div>
                    @endif
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-10 text-center space-y-2">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 mb-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">No Preventive Maintenance History</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            No scheduled preventive maintenance records found in history.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>

</x-app-layout>