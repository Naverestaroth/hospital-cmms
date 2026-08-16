<x-app-layout>

    <div class="space-y-6" x-data="{ showModal: false, selectedTargets: [] }">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Settings & Admin Tools
                </h1>

                <p class="mt-2 text-slate-500">
                    System administration utilities and database maintenance tools.
                </p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1 text-xs font-bold text-amber-700 self-start sm:self-auto shadow-sm">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                Mode: Admin Tools (Sementara)
            </span>
        </div>

        <!-- Navigation Tabs (Admin Tools Active, Profile/Notification/Appearance/User & Role marked for Final Phase) -->
        <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200/80 pb-3 text-sm scrollbar-thin">
            <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 font-semibold text-white shadow-sm flex items-center gap-2">
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/>
                </svg>
                Admin Tools
            </button>

            <button type="button" disabled class="rounded-xl bg-slate-100 px-4 py-2 font-medium text-slate-400 cursor-not-allowed opacity-60 flex items-center gap-1.5 whitespace-nowrap" title="Fitur ini akan dibuat pada tahap final">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></svg>
                Profile (Tahap Final)
            </button>

            <button type="button" disabled class="rounded-xl bg-slate-100 px-4 py-2 font-medium text-slate-400 cursor-not-allowed opacity-60 flex items-center gap-1.5 whitespace-nowrap" title="Fitur ini akan dibuat pada tahap final">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Notification (Tahap Final)
            </button>

            <button type="button" disabled class="rounded-xl bg-slate-100 px-4 py-2 font-medium text-slate-400 cursor-not-allowed opacity-60 flex items-center gap-1.5 whitespace-nowrap" title="Fitur ini akan dibuat pada tahap final">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a7 7 0 1 0 10 10"/></svg>
                Appearance (Tahap Final)
            </button>

            <button type="button" disabled class="rounded-xl bg-slate-100 px-4 py-2 font-medium text-slate-400 cursor-not-allowed opacity-60 flex items-center gap-1.5 whitespace-nowrap" title="Fitur ini akan dibuat pada tahap final">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                User & Role (Tahap Final)
            </button>
        </div>

        <!-- Notification Alerts (Placed in main content flow to the right of sidebar) -->
        @if (session('success'))
            <div class="w-full rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>
                        </svg>
                    </div>
                    <span class="break-words leading-relaxed flex-1 text-emerald-900 text-sm font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="w-full rounded-2xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-900 shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4m0 4h.01"/>
                        </svg>
                    </div>
                    <span class="break-words leading-relaxed flex-1 text-red-900 text-sm font-bold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Admin Tools: Wipe Input Data Component -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">

            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Wipe Input Data
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Bersihkan atau reset data input yang ditentukan dari database CMMS.
                    </p>
                </div>

                <span class="rounded-full border border-red-200 bg-red-50 px-3.5 py-1 text-xs font-bold text-red-700 shadow-sm">
                    Fitur Destruktif
                </span>
            </div>

            <form action="{{ route('settings.wipe') }}" method="POST" id="wipe-form" @submit.prevent>
                @csrf

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block">
                            Pilih Kategori Data Input Yang Ingin Dibersihkan:
                        </label>
                        <div class="flex items-center gap-3 text-xs">
                            <button type="button" @click="selectedTargets = ['assets', 'tickets', 'preventive', 'corrective', 'schedules', 'movements', 'documents', 'spareparts', 'vendors']" class="text-blue-600 hover:underline font-semibold">
                                Pilih Semua
                            </button>
                            <span class="text-slate-300">•</span>
                            <button type="button" @click="selectedTargets = []" class="text-slate-500 hover:underline font-semibold">
                                Batalkan Semua
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        <!-- Checkbox Assets -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('assets') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="assets" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Assets / Equipment</span>
                                <span class="text-xs text-slate-500">Peralatan medis & non-medis rumah sakit</span>
                            </div>
                        </label>

                        <!-- Checkbox Schedules -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('schedules') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="schedules" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Jadwal & History Teknisi</span>
                                <span class="text-xs text-slate-500">Input jadwal Excel & history shift/izin</span>
                            </div>
                        </label>

                        <!-- Checkbox Tickets -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('tickets') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="tickets" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Data Tickets</span>
                                <span class="text-xs text-slate-500">Laporan tiket kerusakan & perbaikan</span>
                            </div>
                        </label>

                        <!-- Checkbox Equipment Movements -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('movements') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="movements" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Equipment Movements</span>
                                <span class="text-xs text-slate-500">Riwayat & mutasi perpindahan peralatan</span>
                            </div>
                        </label>

                        <!-- Checkbox Document Center -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('documents') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="documents" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Document Center</span>
                                <span class="text-xs text-slate-500">Arsip dokumen, manual & sertifikat</span>
                            </div>
                        </label>

                        <!-- Checkbox Preventive Maintenance -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('preventive') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="preventive" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Data Preventive</span>
                                <span class="text-xs text-slate-500">Jadwal Preventive Maintenance</span>
                            </div>
                        </label>

                        <!-- Checkbox Corrective Maintenance -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('corrective') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="corrective" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Data Corrective</span>
                                <span class="text-xs text-slate-500">Laporan Corrective Maintenance</span>
                            </div>
                        </label>

                        <!-- Checkbox Spareparts -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('spareparts') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="spareparts" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Data Spareparts</span>
                                <span class="text-xs text-slate-500">Stok inventaris suku cadang</span>
                            </div>
                        </label>

                        <!-- Checkbox Vendors -->
                        <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                               :class="selectedTargets.includes('vendors') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            <input type="checkbox" name="targets[]" value="vendors" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">Data Vendors</span>
                                <span class="text-xs text-slate-500">Daftar vendor penyedia jasa/peralatan</span>
                            </div>
                        </label>

                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-slate-500 font-medium">
                        * Konfirmasi akan ditampilkan sebelum proses eksekusi pembersihan data.
                    </p>

                    <button
                        type="button"
                        @click="if (selectedTargets.length === 0) { alert('Silakan pilih setidaknya satu kategori data yang ingin dibersihkan.'); } else { showModal = true; }"
                        class="rounded-2xl bg-red-600 px-6 py-3.5 font-semibold text-white transition hover:bg-red-700 shadow-sm flex items-center justify-center gap-2 text-sm self-end sm:self-auto">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Wipe Input
                    </button>
                </div>

                <!-- Confirmation Modal (Clean fixed inset-0 viewport center overlay, z-9999, fully clickable) -->
                <div x-show="showModal"
                     x-cloak
                     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm pointer-events-auto">
                    <div class="w-full max-w-sm rounded-3xl bg-white p-5 shadow-2xl space-y-4 border border-slate-200 pointer-events-auto relative z-[10000]"
                         @click.away="showModal = false">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold flex-shrink-0">
                                ⚠️
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Konfirmasi Wipe Input</h3>
                                <p class="text-xs text-slate-500">Tindakan ini menghapus data permanen.</p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-red-50 border border-red-200 p-3.5 text-xs text-red-800 space-y-1.5">
                            <p class="font-bold">Apakah Anda yakin ingin membersihkan data input?</p>
                            <p class="text-[11px] text-red-700">Kategori data berikut akan di-reset dari database:</p>
                            <ul class="list-disc list-inside font-mono font-semibold space-y-0.5 text-[11px]">
                                <template x-for="target in selectedTargets" :key="target">
                                    <li x-text="target.toUpperCase()"></li>
                                </template>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-1">
                            <button
                                type="button"
                                @click="showModal = false"
                                class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                                Batal
                            </button>

                            <button
                                type="button"
                                @click="showModal = false; document.getElementById('wipe-form').submit()"
                                class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700 shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                Ya, Wipe Data Sekarang
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>

    </div>

</x-app-layout>