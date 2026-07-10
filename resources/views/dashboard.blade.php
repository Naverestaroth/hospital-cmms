<x-app-layout>

    <div class="page-container">

        <!-- Statistics -->

        <div class="section-card card-premium">

            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">

                <div>

                    <h2 class="page-title">
                        Hospital Overview
                    </h2>

                    <p class="page-subtitle">
                        Monitor hospital maintenance summary in real time.
                    </p>

                </div>

                <span class="rounded-full bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700">
                    Live Data
                </span>

            </div>

            <!-- Cards -->
            <div class="grid grid-cols-3 gap-6">

                <!-- Card 1 -->

                <div class="dashboard-stat card-premium stat-assets">

                    <!-- Icon -->
                    <div class="absolute right-5 top-5 opacity-10">

                        <!-- Heroicon nanti -->

                    </div>

                    <p class="page-subtitle">
                        Total Assets
                    </p>

                    <h2 class="mt-4 text-5xl font-bold text-slate-900">
                        {{ $assetCount }}
                    </h2>

                    <p class="mt-3 text-sm text-emerald-600">
                        +2 New Assets This Month
                    </p>

                </div>

                <!-- Card 2 -->

                <div class="dashboard-stat card-premium stat-tickets">

                    <!-- Icon -->
                    <div class="absolute right-5 top-5 opacity-10">

                        {{-- Heroicon Ticket nanti --}}

                    </div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Tickets
                    </p>

                    <h2 class="mt-4 text-5xl font-bold text-slate-900">
                        {{ $ticketCount }}
                    </h2>

                    <p class="mt-3 text-sm text-amber-600">
                        {{ $openTicket }} Open Tickets
                    </p>

                </div>

                <!-- Card 3 -->

                <div class="dashboard-stat card-premium stat-maintenance">

                    <!-- Icon -->
                    <div class="absolute right-5 top-5 opacity-10">

                        {{-- Heroicon Wrench nanti --}}

                    </div>

                    <p class="text-sm font-medium text-slate-500">
                        Maintenance
                    </p>

                    <h2 class="mt-4 text-5xl font-bold text-slate-900">
                        {{ $maintenanceCount }}
                    </h2>

                    <p class="mt-3 text-sm text-sky-600">
                        Preventive & Corrective
                    </p>

                </div>

            </div>

        </div>


        <div class="section-card">
            <div class="mb-8 flex items-center justify-between">

                <div>

                    <h2 class="text-2xl font-bold">
                        Maintenance Status
                    </h2>

                    <p class="mt-1 text-slate-500">
                        Track current maintenance progress.
                    </p>

                </div>

                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm">

                    Current Status

                </span>

            </div>

            <div class="grid gap-6 md:grid-cols-3 xl:grid-cols-4">


                <div class="space-y-2">

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-2">

                            <div class="h-3 w-3 rounded-full bg-emerald-500"></div>

                            <span class="font-medium text-slate-700">
                                Open
                            </span>

                        </div>

                        <span class="font-semibold">
                            {{ $openTicket }}
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-slate-200">

                        <div
                            class="h-2 rounded-full bg-emerald-500 transition-all duration-500"
                            style="width: {{ $openPercent }}%;">
                        </div>

                    </div>

                </div>

                <div class="space-y-2">

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-2">

                            <div class="h-3 w-3 rounded-full bg-amber-500"></div>

                            <span class="font-medium text-slate-700">
                                In Progress
                            </span>

                        </div>

                        <span class="font-semibold">
                            {{ $progressTicket }}
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-slate-200">

                        <div
                            class="h-2 rounded-full bg-emerald-500 transition-all duration-500"
                            style="width: {{ $progressPercent }}%;">
                        </div>

                    </div>

                </div>

                <div class="space-y-2">

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-2">

                            <div class="h-3 w-3 rounded-full bg-sky-500"></div>

                            <span class="font-medium text-slate-700">
                                Completed
                            </span>

                        </div>

                        <span class="font-semibold">
                            {{ $completedTicket }}
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-slate-200">

                        <div
                            class="h-2 rounded-full bg-emerald-500 transition-all duration-500"
                            style="width: {{ $completedPercent }}%;">
                        </div>

                    </div>

                </div>


            </div>
        </div>

        <!-- Bottom Section -->

        <div class="section-card">

            <div class="mb-8 flex items-center justify-between">

                <div>

                    <h2 class="page-title">
                        Recent Maintenance
                    </h2>

                    <p class="page-subtitle">
                        Latest maintenance activities.
                    </p>

                </div>

                <a
                    href="{{ route('tickets.index') }}"
                    class="text-sm font-semibold text-emerald-600 transition hover:text-emerald-700">

                    View All →

                </a>

            </div>

            <div class="space-y-4">

                @foreach($recentTickets as $ticket)

                <div
                    class="flex items-start gap-4 rounded-2xl p-5 transition-all duration-300 hover:bg-slate-50">

                    <div class="mt-2 h-3 w-3 rounded-full bg-emerald-500"></div>

                    <div class="flex-1">

                        <div class="flex items-center justify-between">

                            <h3 class="font-semibold text-slate-900">

                                {{ $ticket->asset->asset_name }}

                            </h3>

                            @if($ticket->status == 'Open')

                            <span class="badge-success">

                                Open

                            </span>

                            @elseif($ticket->status == 'In Progress')

                            <span class="badge-warning">

                                In Progress

                            </span>

                            @else

                            <span class="badge-info">

                                Completed

                            </span>

                            @endif

                        </div>

                        <p class="mt-2 text-sm text-slate-500">

                            {{ $ticket->issue }}

                        </p>

                        <p class="mt-3 text-xs text-slate-400">

                            {{ $ticket->created_at->diffForHumans() }}

                        </p>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        <div class="grid gap-6 xl:grid-cols-3">

            

        </div>

    </div>

</x-app-layout>