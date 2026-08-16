<x-app-layout>
<div class="space-y-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Technician Dashboard</h1>
            <p class="mt-2 text-slate-500">Overview for {{ $technician->name }}.</p>
        </div>
        <a href="{{ route('technicians.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm self-start">
            ← Back to Technicians
        </a>
    </div>

    {{-- Profile + Summary --}}
    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">Technician Profile</h2>
                @if($technician->duty_status === 'On Duty')
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>ON DUTY
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>OFF DUTY
                    </span>
                @endif
            </div>

            <dl class="space-y-3 text-sm">
                <div class="flex flex-col border-b border-slate-100 pb-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Name</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $technician->name }}</dd>
                </div>
                <div class="flex flex-col border-b border-slate-100 pb-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $technician->email ?: '—' }}</dd>
                </div>
                <div class="flex flex-col border-b border-slate-100 pb-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">No. HP</dt>
                    <dd class="mt-1 font-medium text-slate-900">{{ $technician->phone ?: '—' }}</dd>
                </div>
                <div class="flex flex-col">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Current Task Location</dt>
                    <dd class="mt-1 font-semibold text-[#0A4A57]">{{ $technician->active_task_location }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:col-span-2 space-y-4">
            <h2 class="text-xl font-bold text-slate-900">Workload Summary</h2>
            <p class="text-xs text-slate-500">Real-time maintenance activities assigned to {{ $technician->name }}</p>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-amber-200/70 bg-amber-50/50 p-5">
                    <span class="text-xs font-semibold text-amber-700 block">Active Tasks</span>
                    <p class="mt-2 text-3xl font-bold text-amber-900">{{ $activeTickets->count() }}</p>
                    <span class="text-[11px] text-amber-600 font-medium">Pending or In Progress</span>
                </div>
                <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50/50 p-5">
                    <span class="text-xs font-semibold text-emerald-700 block">Completed Tasks</span>
                    <p class="mt-2 text-3xl font-bold text-emerald-900">{{ $completedTickets->count() }}</p>
                    <span class="text-[11px] text-emerald-600 font-medium">Finished Tickets</span>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-5">
                    <span class="text-xs font-semibold text-slate-500 block">Total Assigned</span>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $technician->tickets()->count() }}</p>
                    <span class="text-[11px] text-slate-400 font-medium">Lifetime Assigned</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Ticket tables --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">Active Tickets</h2>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                    {{ $activeTickets->count() }} Active
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Ticket</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($activeTickets as $ticket)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $ticket->ticket_code }}</td>
                                <td class="px-4 py-3">{{ $ticket->room ?: ($ticket->asset?->room ?: '—') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-amber-100 text-amber-800">{{ $ticket->priority }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-blue-100 text-blue-800">{{ $ticket->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 text-xs">No active tickets.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">Completed Tickets</h2>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                    {{ $completedTickets->count() }} Done
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Ticket</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($completedTickets as $ticket)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $ticket->ticket_code }}</td>
                                <td class="px-4 py-3">{{ $ticket->room ?: ($ticket->asset?->room ?: '—') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-emerald-100 text-emerald-800">{{ $ticket->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400 text-xs">No completed tickets.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</x-app-layout>
