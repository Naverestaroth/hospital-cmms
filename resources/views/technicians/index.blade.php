<x-app-layout>
<div
    class="space-y-6"
    x-data="{
        editModalOpen: false,
        exceptionModalOpen: false,
        importModalOpen: false,
        currentTech: { id: null, name: '', email: '', phone: '', duty_status: 'On Duty', manual_override: '' },
        onDutyCount: {{ $onDutyCount }},
        techStatuses: {
            @foreach($technicians as $t)
                {{ $t->id }}: {
                    duty_status: '{{ addslashes($t->duty_status) }}',
                    duty_source_label: '{{ addslashes($t->duty_source_label) }}'
                },
            @endforeach
        },
        init() {
            setInterval(() => {
                this.pollDutyStatuses();
            }, 30000);
        },
        async pollDutyStatuses() {
            try {
                const res = await fetch('{{ route('technicians.duty-statuses') }}');
                if (!res.ok) return;
                const data = await res.json();
                if (data.statuses) {
                    for (const id in data.statuses) {
                        if (this.techStatuses[id]) {
                            this.techStatuses[id].duty_status = data.statuses[id].duty_status;
                            this.techStatuses[id].duty_source_label = data.statuses[id].duty_source_label;
                        } else {
                            this.techStatuses[id] = {
                                duty_status: data.statuses[id].duty_status,
                                duty_source_label: data.statuses[id].duty_source_label
                            };
                        }
                    }
                }
                if (typeof data.onDutyCount !== 'undefined') {
                    this.onDutyCount = data.onDutyCount;
                }
            } catch (e) {}
        },
        openEdit(id, name, email, phone, dutyStatus, manualOverride) {
            this.currentTech = { id, name, email, phone, duty_status: dutyStatus, manual_override: manualOverride || '' };
            this.editModalOpen = true;
        }
    }">

    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 font-medium text-sm flex items-center gap-2 shadow-sm">
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-red-800 font-medium text-sm flex items-center gap-2 shadow-sm">
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Technicians</h1>
            <p class="mt-2 text-slate-500">Auto Duty, shift schedules, overtime/leave exceptions & technician workloads.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            @if(!Auth::user()->isTeknisi())
                <button
                    type="button"
                    @click="importModalOpen = true"
                    class="rounded-2xl border border-teal-300 bg-teal-50 px-4 py-2.5 text-xs font-semibold text-teal-900 hover:bg-teal-100 transition shadow-sm flex items-center gap-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 12 15 15"/>
                    </svg>
                    Import Jadwal
                </button>

                <button
                    type="button"
                    @click="exceptionModalOpen = true"
                    class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-xs font-semibold text-amber-900 hover:bg-amber-100 transition shadow-sm flex items-center gap-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Lembur & Izin
                </button>
            @endif


            <div class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-800 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </span>
                <span x-text="onDutyCount + ' Technicians On Duty'">{{ $onDutyCount }} Technicians On Duty</span>
            </div>
        </div>
    </div>

    {{-- ── FILTER / SEARCH BAR ─────────────────────────────────────────────── --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('technicians.index', ['status' => 'all',     'search' => request('search')]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filterStatus === 'all'      ? 'bg-[#0A4A57] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All ({{ $totalCount }})
            </a>
            <a href="{{ route('technicians.index', ['status' => 'on_duty', 'search' => request('search')]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filterStatus === 'on_duty'  ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                On Duty ({{ $onDutyCount }})
            </a>
            <a href="{{ route('technicians.index', ['status' => 'off_duty','search' => request('search')]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filterStatus === 'off_duty' ? 'bg-slate-700 text-white shadow-sm'  : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Off Duty ({{ $offDutyCount }})
            </a>
        </div>

        <form action="{{ route('technicians.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="status" value="{{ $filterStatus }}">
            <input type="text" name="search" placeholder="Search name, email, or phone…"
                   class="w-full md:w-64 rounded-xl border border-slate-200 px-4 py-2 text-xs focus:border-[#0A4A57] focus:outline-none"
                   value="{{ request('search') }}">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">Search</button>
            @if(request('search'))
                <a href="{{ route('technicians.index', ['status' => $filterStatus]) }}"
                   class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200">Clear</a>
            @endif
        </form>
    </div>

    {{-- ── TECHNICIAN CARDS ────────────────────────────────────────────────── --}}
    <div class="grid gap-6 md:grid-cols-2">
        @forelse($technicians as $tech)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between space-y-6 transition hover:shadow-md">

                <div class="space-y-4">
                    {{-- Avatar / Name / Email / Phone / Badge --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-lg border flex-shrink-0"
                                :class="techStatuses[{{ $tech->id }}]?.duty_status === 'On Duty'
                                    ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                    : 'bg-slate-100 text-slate-600 border-slate-200'">
                                {{ $tech->initials }}
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-xl font-bold text-slate-900 truncate" title="{{ $tech->name }}">
                                    {{ $tech->name }}
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5 truncate" title="{{ $tech->email ?? $tech->user->email ?? '' }}">
                                    {{ $tech->email ?? $tech->user->email ?? 'No email assigned' }}
                                </p>
                                @php $phone = $tech->phone ?? $tech->user->phone ?? null; @endphp
                                @if($phone)
                                    <p class="text-xs text-slate-400 mt-0.5 truncate" title="{{ $phone }}">
                                        {{ $phone }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <template x-if="techStatuses[{{ $tech->id }}]?.duty_status === 'On Duty'">
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>ON DUTY
                                </span>
                            </template>
                            <template x-if="techStatuses[{{ $tech->id }}]?.duty_status !== 'On Duty'">
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 shadow-sm">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>OFF DUTY
                                </span>
                            </template>

                            {{-- Source label indicator --}}
                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md" title="Source of Duty Status"
                                  x-text="techStatuses[{{ $tech->id }}]?.duty_source_label">
                                {{ $tech->duty_source_label }}
                            </span>
                        </div>
                    </div>



                    {{-- Task location --}}
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 space-y-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Unit / Task Location</span>
                        <div class="flex items-center gap-2 text-sm font-semibold
                            {{ $tech->active_task_location !== 'No Active Assignment' ? 'text-slate-800' : 'text-slate-400 italic' }}">
                            <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                            </svg>
                            {{ $tech->active_task_location }}
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-amber-200/70 bg-amber-50/50 p-4">
                            <span class="text-xs font-semibold text-amber-700 block">Jumlah Tugas Aktif</span>
                            <div class="mt-1 text-3xl font-bold text-amber-900">{{ $tech->active_tasks_count }}</div>
                            <span class="text-[11px] text-amber-600 font-medium">Pending & In Progress</span>
                        </div>
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4">
                            <span class="text-xs font-semibold text-slate-500 block">Tugas Selesai</span>
                            <div class="mt-1 text-3xl font-bold text-slate-800">{{ $tech->completed_tasks_count }}</div>
                            <span class="text-[11px] text-slate-400 font-medium">Completed Maintenance</span>
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="pt-3 border-t border-slate-100 flex items-center gap-2">
                    @if(!Auth::user()->isTeknisi())
                        <button
                            type="button"
                            @click="openEdit(
                                {{ $tech->id }},
                                '{{ addslashes($tech->name) }}',
                                '{{ addslashes($tech->email ?? '') }}',
                                '{{ addslashes($tech->phone ?? '') }}',
                                '{{ $tech->getAttributes()['duty_status'] ?? 'Off Duty' }}',
                                '{{ $tech->manual_override ?? '' }}'
                            )"
                            class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 transition hover:bg-slate-50 shadow-sm text-xs flex items-center justify-center gap-1.5">
                            <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </button>
                    @endif

                    <a href="{{ route('technicians.show', $tech) }}"

                       class="flex-1 rounded-2xl bg-[#0A4A57] px-4 py-3 text-center font-semibold text-white transition hover:bg-[#073640] shadow-sm flex items-center justify-center gap-1.5 text-xs">
                        Open Dashboard
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-2 rounded-3xl border border-slate-200 bg-white p-12 text-center text-slate-500">
                <svg viewBox="0 0 24 24" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p class="font-bold text-slate-800 text-base">No technicians found</p>
                <p class="text-xs text-slate-500 mt-1">Try adjusting the filter or search query.</p>
            </div>
        @endforelse
    </div>

    {{-- ── EDIT TECHNICIAN MODAL ────────────────────────────────────────────── --}}
    <div
        x-show="editModalOpen"
        x-cloak
        :class="$root.collapsed ? 'pl-0 md:pl-20' : 'pl-0 md:pl-[19.5rem] lg:pl-[21rem]'"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 transition-all">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="relative z-10 w-full max-w-md max-h-[90vh] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 mb-2 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Edit Technician</h3>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>
            <form :action="'/technicians/' + currentTech.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Technician Name *</label>
                    <input type="text" name="name" x-model="currentTech.name" required class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Email</label>
                    <input type="email" name="email" x-model="currentTech.email" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">No. HP</label>
                    <input type="text" name="phone" x-model="currentTech.phone" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Mode Duty / Status</label>
                    <select name="manual_override" x-model="currentTech.manual_override" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                        <option value="">Auto (Ikuti Jadwal Excel)</option>
                        <option value="On Duty">Manual On Duty</option>
                        <option value="Off Duty">Manual Off Duty</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#0A4A57] text-white text-xs font-bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── EXCEPTION (LEMBUR / IZIN) MODAL ────────────────────────────────── --}}
    <div
        x-show="exceptionModalOpen"
        x-cloak
        :class="$root.collapsed ? 'pl-0 md:pl-20' : 'pl-0 md:pl-[19.5rem] lg:pl-[21rem]'"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 transition-all">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="exceptionModalOpen = false"></div>
        <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Kelola Exception (Lembur / Izin / Sakit)</h3>
                    <p class="text-xs text-slate-500">Lembur otomatis On Duty, Izin/Sakit/Cuti otomatis Off Duty selama periode berlangsung.</p>
                </div>
                <button type="button" @click="exceptionModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            {{-- Form Add Exception --}}
            <form action="{{ route('technicians.exceptions.store') }}" method="POST" class="space-y-4 rounded-2xl bg-slate-50 p-4 border border-slate-200">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Technician *</label>
                        <select name="technician_id" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="">-- Pilih Technician --</option>
                            @foreach($allTechsForSelect as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Exception *</label>
                        <select name="type" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="Lembur">Lembur (Auto ON DUTY)</option>
                            <option value="Izin">Izin (Auto OFF DUTY)</option>
                            <option value="Sakit">Sakit (Auto OFF DUTY)</option>
                            <option value="Cuti">Cuti (Auto OFF DUTY)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Start Date & Time *</label>
                        <input type="text" id="exception_start_at" name="start_at" required placeholder="DD/MM/YYYY HH:mm" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">End Date & Time *</label>
                        <input type="text" id="exception_end_at" name="end_at" required placeholder="DD/MM/YYYY HH:mm" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Notes / Keterangan</label>
                    <input type="text" name="notes" placeholder="e.g. Lembur perbaikan emergency ventilator ICU" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold shadow-sm">Simpan Exception</button>
                </div>
            </form>

            {{-- Recent Active Exceptions Table --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Daftar Exception Terbaru</h4>
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100 text-slate-700">
                            <tr>
                                <th class="p-3">Technician</th>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Waktu Mulai</th>
                                <th class="p-3">Waktu Selesai</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentExceptions as $exc)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 font-semibold text-slate-900">{{ $exc->technician?->name ?? 'Unknown' }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $exc->type === 'Lembur' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $exc->type }} ({{ $exc->override_status }})
                                        </span>
                                    </td>
                                    <td class="p-3 text-slate-600">{{ $exc->start_at ? $exc->start_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="p-3 text-slate-600">{{ $exc->end_at ? $exc->end_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="p-3 text-right">
                                        <form action="{{ route('technicians.exceptions.destroy', $exc->id) }}" method="POST" onsubmit="return confirm('Hapus exception ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-slate-400">Belum ada exception recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── IMPORT SCHEDULE (EXCEL) MODAL ────────────────────────────────────── --}}
    <div
        x-show="importModalOpen"
        x-cloak
        :class="$root.collapsed ? 'pl-0 md:pl-20' : 'pl-0 md:pl-[19.5rem] lg:pl-[21rem]'"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 transition-all">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="importModalOpen = false"></div>
        <div class="relative z-10 w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Import Jadwal Technician (Excel)</h3>
                <button type="button" @click="importModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>
            <form action="{{ route('technicians.import-schedule') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Upload File Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv,.txt" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>
                <div class="text-center text-xs text-slate-400 font-bold uppercase">— Atau Paste Text Jadwal —</div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Paste Format CSV / Text Matrix</label>
                    <textarea name="schedule_text" rows="4" placeholder="Technician,Date,Start_Time,End_Time,Shift_Name" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-mono"></textarea>
                </div>
                <div class="pt-2 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="importModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#0A4A57] text-white text-xs font-bold shadow-md hover:bg-[#083a45]">Import Schedule</button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#exception_start_at", {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    altInput: true,
                    altFormat: "d/m/Y H:i",
                    allowInput: true
                });
                flatpickr("#exception_end_at", {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    altInput: true,
                    altFormat: "d/m/Y H:i",
                    allowInput: true
                });
            }
        });
    </script>

</div>
</x-app-layout>
