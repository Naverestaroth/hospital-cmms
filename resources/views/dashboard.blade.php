<x-app-layout>

    <div class="space-y-8">

        <!-- Statistics -->

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    Total Assets
                </p>

                <h2 class="mt-3 text-4xl font-bold text-slate-900">
                    {{ $assetCount }}
                </h2>

                <p class="mt-2 text-sm text-emerald-600">
                    +12 this month
                </p>

            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    Open Tickets
                </p>

                <h2 class="mt-3 text-4xl font-bold text-red-500">
                    {{ $ticketCount }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Waiting for technician
                </p>

            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    Preventive Maintenance
                </p>

                <h2 class="mt-3 text-4xl font-bold text-amber-500">
                    {{ $preventiveCount }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Scheduled this month
                </p>

            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    Active Technicians
                </p>

                <h2 class="mt-3 text-4xl font-bold text-sky-600">
                    {{ $correctiveCount }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Available today
                </p>

            </div>


        </div>
        
        <div class="grid gap-6 md:grid-cols-3 xl:grid-cols-4"> 
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    Open Tickets
                </p>

                <h2 class="mt-3 text-4xl font-bold text-sky-600">
                    {{ $openTicket }}
                </h2>

            </div>
    
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    In Progess
                </p>

                <h2 class="mt-3 text-4xl font-bold text-sky-600">
                    {{ $progressTicket }}
                </h2>

            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <p class="text-sm text-slate-500">
                    Completed Tickets
                </p>

                <h2 class="mt-3 text-4xl font-bold text-sky-600">
                    {{ $completedTicket }}
                </h2>

            </div>
        </div>

        <!-- Bottom Section -->

        <div class="grid gap-6 xl:grid-cols-3">

            <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <h3 class="text-xl font-semibold text-slate-900">
                    Recent Maintenance Tickets
                </h3>

                <div class="mt-6 space-y-4">

                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">

                        <div>

                            <h4 class="font-semibold">
                                Ventilator ICU-02
                            </h4>

                            <p class="text-sm text-slate-500">
                                Electrical Issue
                            </p>

                        </div>

                        <span class="rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-600">
                            Open
                        </span>

                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">

                        <div>

                            <h4 class="font-semibold">
                                X-Ray Room AC
                            </h4>

                            <p class="text-sm text-slate-500">
                                Routine Maintenance
                            </p>

                        </div>

                        <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-600">
                            In Progress
                        </span>

                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">

                        <div>

                            <h4 class="font-semibold">
                                Patient Monitor
                            </h4>

                            <p class="text-sm text-slate-500">
                                Sensor Replacement
                            </p>

                        </div>

                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-600">
                            Completed
                        </span>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <h3 class="text-xl font-semibold text-slate-900">
                    Upcoming Schedule
                </h3>

                <div class="mt-6 space-y-4">

                    <div class="rounded-2xl bg-slate-50 p-4">

                        <p class="font-medium">
                            CT Scan
                        </p>

                        <p class="text-sm text-slate-500">
                            08 July 2026
                        </p>

                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">

                        <p class="font-medium">
                            Generator
                        </p>

                        <p class="text-sm text-slate-500">
                            09 July 2026
                        </p>

                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">

                        <p class="font-medium">
                            Oxygen System
                        </p>

                        <p class="text-sm text-slate-500">
                            11 July 2026
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>