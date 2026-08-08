<x-app-layout>

    <div class="space-y-6 max-w-6xl">

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Equipment Movement History
                </h1>
                <p class="mt-1 text-slate-500 text-sm">
                    Administrative tracking log of medical equipment moved to the IPSRS Workshop for repair.
                </p>
            </div>

            <!-- Header Quick Stats -->
            <div class="flex items-center gap-2">
                <a href="{{ route('tickets.movements', ['status' => 'in_workshop']) }}" class="rounded-2xl border border-amber-200 bg-amber-50 px-3.5 py-2 text-xs font-bold text-amber-800 shadow-xs hover:bg-amber-100 transition">
                    In Workshop: {{ $inWorkshopCount }}
                </a>
                <a href="{{ route('tickets.movements', ['status' => 'returned']) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs font-bold text-emerald-800 shadow-xs hover:bg-emerald-100 transition">
                    Returned: {{ $returnedCount }}
                </a>
                <a href="{{ route('tickets.movements', ['status' => 'all']) }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-100 transition">
                    All: {{ $allCount }}
                </a>
            </div>
        </div>

        <!-- Filter Bar & Search -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                
                <!-- Status Filter Pills -->
                <div class="inline-flex rounded-2xl bg-slate-100 p-1.5 border border-slate-200/80 text-xs font-semibold">
                    <a href="{{ route('tickets.movements', array_merge(request()->query(), ['status' => 'all', 'page' => 1])) }}"
                       class="rounded-xl px-4 py-2 transition {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                        All Movements ({{ $allCount }})
                    </a>

                    <a href="{{ route('tickets.movements', array_merge(request()->query(), ['status' => 'in_workshop', 'page' => 1])) }}"
                       class="rounded-xl px-4 py-2 transition {{ $statusFilter === 'in_workshop' ? 'bg-white text-amber-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                        In Workshop ({{ $inWorkshopCount }})
                    </a>

                    <a href="{{ route('tickets.movements', array_merge(request()->query(), ['status' => 'returned', 'page' => 1])) }}"
                       class="rounded-xl px-4 py-2 transition {{ $statusFilter === 'returned' ? 'bg-white text-emerald-900 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                        Returned to Room ({{ $returnedCount }})
                    </a>
                </div>

                <!-- Search Input -->
                <form action="{{ route('tickets.movements') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search equipment, ticket, room, staff..."
                            class="rounded-2xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-xs text-slate-700 focus:bg-white focus:border-amber-500 focus:outline-none w-64 transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <button type="submit" class="rounded-2xl bg-amber-600 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-700 transition shadow-xs">
                        Search
                    </button>

                    @if($search)
                        <a href="{{ route('tickets.movements', ['status' => $statusFilter]) }}" class="text-xs text-slate-400 hover:text-slate-600">Clear</a>
                    @endif
                </form>

            </div>
        </div>

        <!-- History Table Card -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-lg font-bold text-slate-900">Equipment Movement Log</h2>
                <span class="text-xs font-semibold text-slate-500">{{ $tickets->total() }} record(s)</span>
            </div>

            @if($tickets->count() > 0)
                <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Ticket Code</th>
                                <th class="px-4 py-3 text-left">Asset</th>
                                <th class="px-4 py-3 text-left">Room</th>
                                <th class="px-4 py-3 text-left">Outgoing Date</th>
                                <th class="px-4 py-3 text-left">Returned Date</th>
                                <th class="px-4 py-3 text-left">Handed Over By</th>
                                <th class="px-4 py-3 text-left">Received By</th>
                                <th class="px-4 py-3 text-left">Location Status</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($tickets as $ticket)
                                @php
                                    $isInWorkshop = empty($ticket->returned_date);
                                    $locationLabel = $isInWorkshop ? 'In Workshop' : 'In Room';
                                    $locationBadge = $isInWorkshop ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $ticket->ticket_code }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-800">
                                        {{ $ticket->asset?->asset_name ?? 'N/A' }}
                                        <span class="block text-[10px] font-mono text-slate-400 font-normal">{{ $ticket->asset?->asset_code }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $ticket->room ?? $ticket->asset?->room ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $ticket->sent_to_workshop_date ? \Carbon\Carbon::parse($ticket->sent_to_workshop_date)->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $ticket->returned_date ? \Carbon\Carbon::parse($ticket->returned_date)->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="font-medium text-slate-800 block">{{ $ticket->sent_by ?: '—' }}</span>
                                        @if($ticket->returned_by)
                                            <span class="text-[10px] text-slate-400 block">Ret: {{ $ticket->returned_by }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="font-medium text-slate-800 block">{{ $ticket->received_by_workshop ?: '—' }}</span>
                                        @if($ticket->received_by_user)
                                            <span class="text-[10px] text-slate-400 block">Ret: {{ $ticket->received_by_user }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full border px-2.5 py-0.5 text-[10px] font-bold {{ $locationBadge }}">
                                            {{ $locationLabel }}
                                        </span>
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
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-700 mb-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">No Equipment Movements Recorded</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        No equipment movement records match the selected filter.
                    </p>
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
