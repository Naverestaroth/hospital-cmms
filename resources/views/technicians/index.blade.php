<x-app-layout>

    <div class="space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Technicians
                </h1>
                <p class="mt-2 text-slate-500">
                    Overview of active technicians on duty, room assignments, and maintenance workloads.
                </p>
            </div>

            <!-- Active On-Duty Status Badge -->
            <div class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-800 self-start sm:self-auto shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </span>
                2 Technicians On Duty
            </div>
        </div>

        @php
            $activeTechnicians = [
                [
                    'id' => 1,
                    'name' => 'Andi Pratama',
                    'role' => 'Senior Maintenance Technician',
                    'unit' => 'Gedung A (Main Hospital Wing)',
                    'status' => 'ON DUTY',
                    'active_tasks' => 5,
                    'completed_tasks' => 12,
                    'initials' => 'AP',
                    'avatar_bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                ],
                [
                    'id' => 2,
                    'name' => 'Budi Santoso',
                    'role' => 'Biomedical Technician',
                    'unit' => 'Gedung B (Maintenance Block)',
                    'status' => 'ON DUTY',
                    'active_tasks' => 3,
                    'completed_tasks' => 25,
                    'initials' => 'BS',
                    'avatar_bg' => 'bg-blue-100 text-blue-800 border-blue-200',
                ],
            ];
        @endphp

        <!-- Active Technicians List Grid -->
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($activeTechnicians as $tech)
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between space-y-6 transition hover:shadow-md">
                    
                    <div class="space-y-4">
                        <!-- Top Header: Avatar, Name, Role & Status Aktif -->
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-lg border {{ $tech['avatar_bg'] }}">
                                    {{ $tech['initials'] }}
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-slate-900">
                                        {{ $tech['name'] }}
                                    </h2>
                                    <p class="text-xs font-medium text-slate-500 mt-0.5">
                                        {{ $tech['role'] }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status Aktif Badge -->
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                {{ $tech['status'] }}
                            </span>
                        </div>

                        <!-- Unit / Ruangan -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 space-y-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">
                                Unit / Ruangan Assignment
                            </span>
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                                </svg>
                                {{ $tech['unit'] }}
                            </div>
                        </div>

                        <!-- Stat Counters Row (Jumlah Tugas Aktif) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-amber-200/70 bg-amber-50/50 p-4">
                                <span class="text-xs font-semibold text-amber-700 block">Jumlah Tugas Aktif</span>
                                <div class="mt-1 text-3xl font-bold text-amber-900">
                                    {{ $tech['active_tasks'] }}
                                </div>
                                <span class="text-[11px] text-amber-600 font-medium">Pending & In Progress</span>
                            </div>

                            <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4">
                                <span class="text-xs font-semibold text-slate-500 block">Tugas Selesai</span>
                                <div class="mt-1 text-3xl font-bold text-slate-800">
                                    {{ $tech['completed_tasks'] }}
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium">Completed Maintenance</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Open Dashboard -->
                    <div class="pt-2 border-t border-slate-100">
                        <a
                            href="{{ route('technicians.show', ['id' => $tech['id']]) }}"
                            class="w-full rounded-2xl bg-emerald-600 px-5 py-3.5 text-center font-semibold text-white transition hover:bg-emerald-700 shadow-sm flex items-center justify-center gap-2 text-sm">
                            Open Dashboard
                            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </div>

</x-app-layout>
