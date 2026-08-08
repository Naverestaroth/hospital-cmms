<x-app-layout>
    <div class="space-y-8">
        <!-- Page Header -->
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('technicians.index') }}" class="ds-btn ds-btn-circle ds-btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ $name }}'s Workspace
                    </h1>
                    <p class="mt-1 text-slate-500">
                        Admin monitoring page for technician activities.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            <!-- Left Column -->
            <div class="col-span-1 space-y-8 lg:col-span-1">
                <!-- Card 1: Technician Information -->
                <div class="ds-card">
                    <div class="ds-card-body">
                        <h2 class="ds-card-title">Technician Information</h2>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-600">Name</span>
                                <span class="text-slate-800">Andi Pratama</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-600">Phone Number</span>
                                <span class="text-slate-800">0812-3456-7890</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-600">Main Building</span>
                                <span class="text-slate-800">Gedung A</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-slate-600">Status</span>
                                <span class="ds-badge ds-badge-success ds-badge-outline">Online</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Assigned Buildings -->
                <div class="ds-card">
                    <div class="ds-card-body">
                        <h2 class="ds-card-title">Assigned Buildings</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <div class="ds-badge ds-badge-lg">Gedung A</div>
                            <div class="ds-badge ds-badge-lg">Gedung B</div>
                            <div class="ds-badge ds-badge-lg">Gedung C (ICU)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-span-1 space-y-8 lg:col-span-2">

                <!-- Card 5: Summary -->
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div class="ds-stat ds-card">
                        <div class="ds-stat-title">Active Tasks</div>
                        <div class="ds-stat-value">5</div>
                    </div>
                    <div class="ds-stat ds-card">
                        <div class="ds-stat-title">Completed Corrective</div>
                        <div class="ds-stat-value">8</div>
                    </div>
                    <div class="ds-stat ds-card">
                        <div class="ds-stat-title">Completed Preventive</div>
                        <div class="ds-stat-value">32</div>
                    </div>
                    <div class="ds-stat ds-card">
                        <div class="ds-stat-title">Total Maintenance</div>
                        <div class="ds-stat-value">40</div>
                    </div>
                </div>

                <!-- Card 3: Active Tasks -->
                <div class="ds-card">
                    <div class="ds-card-body">
                        <h2 class="ds-card-title">Active Tasks</h2>
                        <div class="mt-4 space-y-6">
                            <!-- Corrective Maintenance Table -->
                            <div>
                                <h3 class="font-semibold">Corrective Maintenance</h3>
                                <div class="overflow-x-auto">
                                    <table class="ds-table ds-table-zebra mt-2">
                                        <thead>
                                            <tr>
                                                <th>Asset</th>
                                                <th>Location</th>
                                                <th>Priority</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Defibrillator</td>
                                                <td>Gedung A / ICU Lt. 2</td>
                                                <td><span class="ds-badge ds-badge-error">High</span></td>
                                            </tr>
                                            <tr>
                                                <td>Patient Monitor</td>
                                                <td>Gedung B / Ruang 201</td>
                                                <td><span class="ds-badge ds-badge-warning">Medium</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Preventive Maintenance Table -->
                            <div>
                                <h3 class="font-semibold">Preventive Maintenance</h3>
                                <div class="overflow-x-auto">
                                    <table class="ds-table ds-table-zebra mt-2">
                                        <thead>
                                            <tr>
                                                <th>Asset</th>
                                                <th>Location</th>
                                                <th>Schedule</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Ventilator</td>
                                                <td>Gedung A / ICU Lt. 1</td>
                                                <td>25 Jul 2024</td>
                                            </tr>
                                            <tr>
                                                <td>ECG Machine</td>
                                                <td>Gedung C / Poli Jantung</td>
                                                <td>28 Jul 2024</td>
                                            </tr>
                                             <tr>
                                                <td>X-Ray Machine</td>
                                                <td>Gedung B / Radiologi</td>
                                                <td>30 Jul 2024</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Maintenance History -->
                <div class="ds-card">
                    <div class="ds-card-body">
                        <h2 class="ds-card-title">Maintenance History (Last 30 Days)</h2>
                        <div class="mt-4 space-y-6">
                            <!-- Corrective History Table -->
                            <div>
                                <h3 class="font-semibold">Corrective History</h3>
                                <div class="overflow-x-auto">
                                    <table class="ds-table ds-table-zebra mt-2">
                                        <thead>
                                            <tr>
                                                <th>Asset</th>
                                                <th>Problem</th>
                                                <th>Completed Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Infusion Pump</td>
                                                <td>Error code E-05</td>
                                                <td>20 Jul 2024</td>
                                            </tr>
                                            <tr>
                                                <td>Hospital Bed</td>
                                                <td>Remote not working</td>
                                                <td>18 Jul 2024</td>
                                            </tr>
                                            <tr>
                                                <td>Suction Machine</td>
                                                <td>Low suction power</td>
                                                <td>15 Jul 2024</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Preventive History Table -->
                            <div>
                                <h3 class="font-semibold">Preventive History</h3>
                                <div class="overflow-x-auto">
                                    <table class="ds-table ds-table-zebra mt-2">
                                        <thead>
                                            <tr>
                                                <th>Asset</th>
                                                <th>Checklist</th>
                                                <th>Completed Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>USG Machine</td>
                                                <td>Monthly Check</td>
                                                <td>12 Jul 2024</td>
                                            </tr>
                                            <tr>
                                                <td>Autoclave</td>
                                                <td>Quarterly Calibration</td>
                                                <td>10 Jul 2024</td>
                                            </tr>
                                            <tr>
                                                <td>Anesthesia Machine</td>
                                                <td>Monthly Check</td>
                                                <td>05 Jul 2024</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>