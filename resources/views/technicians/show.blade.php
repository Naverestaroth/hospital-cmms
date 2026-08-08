<x-app-layout>
    <div class="page-container">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="page-title">Technician Dashboard</h1>
                <p class="page-subtitle">Overview for {{ $technician['name'] }}.</p>
            </div>
            <a href="{{ route('technicians.index') }}" class="ds-button-secondary">Back to Technicians</a>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="section-card">
                <h2 class="text-xl font-bold text-slate-900">Technician Information</h2>
                <p class="mt-1 text-xs text-slate-500">Personal and contact details</p>
                <dl class="mt-6 space-y-4 text-sm">
                    <div class="flex flex-col border-b border-slate-100 pb-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Name</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $technician['name'] }}</dd>
                    </div>
                    <div class="flex flex-col border-b border-slate-100 pb-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Role</dt>
                        <dd class="mt-1 font-medium text-slate-900">Maintenance Technician</dd>
                    </div>
                    <div class="flex flex-col border-b border-slate-100 pb-3">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $technician['email'] }}</dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phone</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $technician['phone'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="section-card md:col-span-2">
                <h2 class="text-xl font-bold text-slate-900">Assigned Buildings</h2>
                <p class="mt-1 text-xs text-slate-500">Facilities under technician supervision</p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($technician['buildings'] as $building)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition hover:bg-slate-50">
                            <p class="text-sm font-bold text-slate-900">{{ $building['name'] }}</p>
                            <p class="mt-2 text-xs text-slate-500 leading-relaxed">{{ $building['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-4">
            <!-- Active Tasks -->
            <div class="dashboard-stat card-premium glow-hover stat-tickets">
                <p class="text-sm font-medium text-slate-500">Active Tasks</p>
                <h2 class="mt-4 text-4xl font-bold text-slate-900">{{ $technician['summary']['active_tasks'] }}</h2>
                <p class="mt-3 text-sm text-amber-600">Assigned tickets</p>
            </div>

            <!-- Completed Corrective -->
            <div class="dashboard-stat card-premium glow-hover stat-assets">
                <p class="text-sm font-medium text-slate-500">Completed Corrective</p>
                <h2 class="mt-4 text-4xl font-bold text-slate-900">{{ $technician['summary']['completed_corrective'] }}</h2>
                <p class="mt-3 text-sm text-emerald-600">Corrective tasks</p>
            </div>

            <!-- Completed Preventive -->
            <div class="dashboard-stat card-premium glow-hover stat-maintenance">
                <p class="text-sm font-medium text-slate-500">Completed Preventive</p>
                <h2 class="mt-4 text-4xl font-bold text-slate-900">{{ $technician['summary']['completed_preventive'] }}</h2>
                <p class="mt-3 text-sm text-sky-600">Scheduled checkups</p>
            </div>

            <!-- Total Maintenance -->
            <div class="dashboard-stat card-premium glow-hover">
                <p class="text-sm font-medium text-slate-500">Total Maintenance</p>
                <h2 class="mt-4 text-4xl font-bold text-slate-900">{{ $technician['summary']['total_maintenance'] }}</h2>
                <p class="mt-3 text-sm text-slate-500">Lifetime tasks completed</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Active Corrective -->
            <div class="section-card">
                <h2 class="text-xl font-bold text-slate-900">Active Corrective</h2>
                <p class="mt-1 text-xs text-slate-500">Ongoing corrective tickets</p>
                
                <div class="ds-table mt-6 overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Ticket</th>
                                <th class="px-6 py-4">Building</th>
                                <th class="px-6 py-4">Due</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($technician['active_corrective'] as $ticket)
                                <tr>
                                    <td class="px-6 py-4">{{ $ticket['ticket'] }}</td>
                                    <td class="px-6 py-4">{{ $ticket['building'] }}</td>
                                    <td class="px-6 py-4">{{ $ticket['due'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Preventive -->
            <div class="section-card">
                <h2 class="text-xl font-bold text-slate-900">Active Preventive</h2>
                <p class="mt-1 text-xs text-slate-500">Upcoming maintenance schedules</p>
                
                <div class="ds-table mt-6 overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Schedule</th>
                                <th class="px-6 py-4">Building</th>
                                <th class="px-6 py-4">Due</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($technician['active_preventive'] as $schedule)
                                <tr>
                                    <td class="px-6 py-4">{{ $schedule['schedule'] }}</td>
                                    <td class="px-6 py-4">{{ $schedule['building'] }}</td>
                                    <td class="px-6 py-4">{{ $schedule['due'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Maintenance History -->
            <div class="section-card">
                <h2 class="text-xl font-bold text-slate-900">Maintenance History</h2>
                <p class="mt-1 text-xs text-slate-500">Recently completed tasks</p>
                
                <div class="ds-table mt-6 overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Activity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($technician['history'] as $entry)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $entry['date'] }}</td>
                                    <td class="px-6 py-4">{{ $entry['activity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
