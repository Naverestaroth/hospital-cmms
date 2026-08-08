<x-app-layout>

    <div class="space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Ticket Management
                </h1>
                <p class="mt-2 text-slate-500">
                    Manage hospital service tickets, approvals, technician assignments, and progress.
                </p>
            </div>

            <a href="{{ route('tickets.create') }}" class="ds-button-primary">
                + New Ticket
            </a>
        </div>

        <!-- Filter Tabs & Search -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
            
            <!-- Workflow Filter Badges -->
            <div class="flex flex-wrap gap-2 text-xs border-b border-slate-100 pb-4">
                <a href="{{ route('tickets.index') }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All ({{ $statusCounts['all'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Waiting Approval']) }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Waiting Approval' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Waiting Approval ({{ $statusCounts['waiting_approval'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Open']) }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Open' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Open ({{ $statusCounts['open'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Assigned']) }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Assigned' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    Assigned ({{ $statusCounts['assigned'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'In Progress']) }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'In Progress' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                    In Progress ({{ $statusCounts['in_progress'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Repair Completed']) }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Repair Completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Completed ({{ $statusCounts['completed'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Closed']) }}" 
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Closed' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Closed ({{ $statusCounts['closed'] }})
                </a>
            </div>

            <!-- Search Input -->
            <form action="{{ route('tickets.index') }}" method="GET" class="flex gap-4">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input
                    type="text"
                    name="search"
                    placeholder="Search ticket ID, asset, room, reported by, technician..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button type="submit" class="rounded-xl border border-slate-200 px-5 text-sm font-medium hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('tickets.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">
                    Reset
                </a>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    @php
                    function sortUrl($field) {
                        return request()->fullUrlWithQuery([
                            'sort' => $field,
                            'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc'
                        ]);
                    }
                    @endphp

                    <tr class="border-t transition hover:bg-slate-50 text-xs font-semibold text-slate-600 uppercase">
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('ticket_code') }}">Ticket ID</a>
                        </th>
                        <th class="px-6 py-4 text-left">Room</th>
                        <th class="px-6 py-4 text-left">Asset Name</th>
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('reported_by') }}">Reported By</a>
                        </th>
                        <th class="px-6 py-4 text-left">Technician(s)</th>
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('priority') }}">Priority</a>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('status') }}">Status</a>
                        </th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $ticket)
                    <tr class="transition hover:bg-slate-50/80">
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $tickets->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:underline">
                                {{ $ticket->ticket_code }}
                            </a>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $ticket->room ?? $ticket->asset?->room ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-slate-800">
                            {{ $ticket->asset?->asset_name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $ticket->reported_by }}
                            <span class="block text-xs text-slate-400">({{ $ticket->creator_type ?? 'User' }})</span>
                        </td>

                        <!-- Assigned Technicians Badges -->
                        <td class="px-6 py-4 text-sm">
                            @if($ticket->technicians->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($ticket->technicians as $tech)
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 border border-slate-200 px-2 py-0.5 text-xs font-medium text-slate-700">
                                            {{ $tech->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">Unassigned</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm">
                            @if($ticket->priority === 'High')
                                <span class="inline-flex items-center rounded-full bg-red-50 border border-red-200 px-2.5 py-0.5 text-xs font-medium text-red-700">High</span>
                            @elseif($ticket->priority === 'Medium')
                                <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-medium text-amber-700">Medium</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-700">Low</span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusClasses = match($ticket->status) {
                                    'Waiting Approval' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'Approved', 'Open' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Assigned', 'Accepted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'In Progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Waiting Sparepart', 'Waiting Vendor', 'Waiting User' => 'bg-amber-100 text-amber-900 border-amber-300',
                                    'Repair Completed', 'Waiting Corrective Report', 'Corrective Report Completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'Closed' => 'bg-slate-100 text-slate-700 border-slate-300',
                                    'Rejected', 'Cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $statusClasses }}">
                                {{ $ticket->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('tickets.show', $ticket) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                    View
                                </a>
                                <a href="{{ route('tickets.edit', $ticket) }}" class="text-xs font-semibold text-emerald-700 hover:underline">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-10 text-center text-sm text-slate-500">
                            No ticket data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>

    </div>

</x-app-layout>