@php
$rooms = [
    'ICU',
    'IGD',
    'Radiology',
    'Operating Room',
    'Laboratory',
    'NICU',
    'PICU',
    'CSSD',
];

$responses = [
    '≤ 15 Minutes',
    '> 15 Minutes',
];

$serviceTypes = [
    'Instalasi Baru',
    'Pemantauan Fungsi',
    'Kalibrasi',
    'Pemeliharaan / Maintenance',
    'Perbaikan',
    'Other',
];

$inspections = [
    'Uji fisik',
    'Uji fungsi alat',
    'Setting parameter / kalibrasi',
    'Mekanik',
    'Kelistrikan / Elektrikal',
    'Pemeriksaan / Cleaning-up',
    'Troubleshooting',
    'Other',
];

$results = [
    'Fasilitas berfungsi baik',
    'Berfungsi tidak sempurna',
    'Tidak berfungsi',
    'Dihapuskan',
    'Other',
];

$techniciansList = [
    'Susanto',
    'Hutami',
    'Zaky',
    'Lisa',
    'Syarif',
    'Syiefa',
    'Ghazali',
];

$prefillTicketTechs = isset($ticket) && $ticket ? $ticket->technicians->pluck('name')->toArray() : [];
@endphp

<x-app-layout>

    <div class="max-w-4xl space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Create Service Report Elektromedis
            </h1>
            <p class="mt-2 text-slate-500">
                Official IPSRS Service Report Elektromedis form.
            </p>
        </div>

        @if(isset($ticket) && $ticket)
            <div class="rounded-2xl border border-blue-200 bg-blue-50/90 p-4 space-y-1">
                <div class="flex items-center gap-2 text-blue-900 font-bold text-sm">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Linked Ticket Reference: {{ $ticket->ticket_code }}
                </div>
                <p class="text-xs text-blue-800">
                    Equipment data and reported symptoms have been pre-filled from Ticket <strong>{{ $ticket->ticket_code }}</strong> (Reported by: {{ $ticket->reported_by }}).
                </p>
            </div>
        @endif

        <form action="{{ route('correctives.store') }}" method="POST" id="corrective-form" class="space-y-6">
            @csrf

            @if(isset($ticket) && $ticket)
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                    <div class="text-sm font-semibold text-red-700">
                        Please fix the following errors:
                    </div>
                    <ul class="mt-2 list-disc pl-6 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <script>
                window.PREVENTIVE_ASSET_API = {
                    assetsByRoomUrl: @json(route('preventive-assets.by-room')),
                    assetDetailUrlTemplate: @json(route('preventive-assets.detail', ['asset' => '__ASSET__']))
                };
            </script>

            {{-- Hidden asset_code to store behind the scenes --}}
            <input type="hidden" name="asset_code" id="asset_code" value="{{ old('asset_code', isset($ticket) ? $ticket->asset?->asset_code : '') }}">

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-6">

                {{-- 1. Tanggal Laporan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        1. Tanggal Laporan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="repair_date"
                        value="{{ old('repair_date', date('Y-m-d')) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                        required>
                </div>

                {{-- 2. Jam Laporan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        2. Jam Laporan
                    </label>
                    <input
                        type="time"
                        name="jam_laporan"
                        value="{{ old('jam_laporan', isset($ticket) ? $ticket->created_at->format('H:i') : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 3. Jam Visit --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        3. Jam Visit
                    </label>
                    <input
                        type="time"
                        name="jam_visit"
                        value="{{ old('jam_visit', date('H:i')) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 4. Time Response --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        4. Time Response
                    </label>
                    <select
                        name="response_time"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <option value="">Select Response Time</option>
                        @foreach($responses as $response)
                            <option value="{{ $response }}" {{ old('response_time') == $response ? 'selected' : '' }}>
                                {{ $response }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 5. Service Report Type --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        5. Service Report Type
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($serviceTypes as $item)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="service_type[]"
                                    value="{{ $item }}"
                                    {{ is_array(old('service_type')) && in_array($item, old('service_type')) ? 'checked' : ($item === 'Perbaikan' ? 'checked' : '') }}
                                    class="h-5 w-5 rounded border-slate-300 text-emerald-600">
                                <span>{{ $item }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 6. Ruangan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        6. Ruangan
                    </label>
                    <input
                        type="text"
                        name="room"
                        id="room_input"
                        value="{{ old('room', isset($ticket) ? ($ticket->room ?? $ticket->asset?->room) : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                        placeholder="e.g. ICU / IGD">
                </div>

                {{-- 7. Nama Alat --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        7. Nama Alat
                    </label>
                    <input
                        type="text"
                        name="asset_name"
                        id="asset_name"
                        value="{{ old('asset_name', isset($ticket) ? $ticket->asset?->asset_name : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                        placeholder="e.g. Patient Monitor">
                </div>

                {{-- 8. Merk --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        8. Merk
                    </label>
                    <input
                        type="text"
                        name="brand"
                        id="brand"
                        value="{{ old('brand', isset($ticket) ? $ticket->asset?->brand : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 9. Type --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        9. Type
                    </label>
                    <input
                        type="text"
                        name="type"
                        id="type"
                        value="{{ old('type', isset($ticket) ? $ticket->asset?->type : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 10. Serial Number --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        10. Serial Number
                    </label>
                    <input
                        type="text"
                        name="serial_number"
                        id="serial_number"
                        value="{{ old('serial_number', isset($ticket) ? $ticket->asset?->serial_number : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 11. Installation Year --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        11. Installation Year
                    </label>
                    <input
                        type="text"
                        name="tanggal_instal"
                        id="tanggal_instal"
                        value="{{ old('tanggal_instal', isset($ticket) ? $ticket->asset?->procurement_year : '') }}"
                        placeholder="e.g. 2024"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 12. Distributor --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        12. Distributor
                    </label>
                    <input
                        type="text"
                        name="distributor"
                        value="{{ old('distributor') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 13. Pemeriksaan --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        13. Pemeriksaan
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($inspections as $item)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="inspection[]"
                                    value="{{ $item }}"
                                    {{ is_array(old('inspection')) && in_array($item, old('inspection')) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-slate-300 text-emerald-600">
                                <span>{{ $item }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 14. Problem / Diagnosa Kerusakan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        14. Problem / Diagnosa Kerusakan
                    </label>
                    <textarea
                        name="problem"
                        rows="4"
                        placeholder="Deskripsikan problem kerusakan..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('problem', isset($ticket) ? $ticket->issue : '') }}</textarea>
                </div>

                {{-- 15. Solution / Tindakan yang dilakukan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        15. Solution / Tindakan yang dilakukan
                    </label>
                    <textarea
                        name="solution"
                        rows="4"
                        placeholder="Deskripsikan tindakan solusi yang dilakukan..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('solution', isset($ticket) ? $ticket->work_performed : '') }}</textarea>
                </div>

                {{-- 16. Sparepart yang digunakan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        16. Sparepart yang digunakan
                    </label>
                    <input
                        type="text"
                        name="sparepart"
                        value="{{ old('sparepart') }}"
                        placeholder="e.g. Oxygen sensor module"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 17. Jumlah Sparepart --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        17. Jumlah Sparepart
                    </label>
                    <input
                        type="number"
                        min="1"
                        name="quantity"
                        value="{{ old('quantity') }}"
                        placeholder="e.g. 1"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 18. Keterangan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        18. Keterangan
                    </label>
                    <textarea
                        name="notes"
                        rows="4"
                        placeholder="Tambahkan keterangan tambahan jika ada..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>

                {{-- 19. Hasil Pemeriksaan --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        19. Hasil Pemeriksaan
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($results as $result)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50 cursor-pointer">
                                <input
                                    type="radio"
                                    name="inspection_result"
                                    value="{{ $result }}"
                                    {{ old('inspection_result', 'Fasilitas berfungsi baik') == $result ? 'checked' : '' }}
                                    class="h-5 w-5 text-emerald-600 focus:ring-emerald-500">
                                <span>{{ $result }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 20. Teknisi --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        20. Teknisi
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($techniciansList as $tech)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="technician[]"
                                    value="{{ $tech }}"
                                    {{ (is_array(old('technician')) && in_array($tech, old('technician'))) || in_array($tech, $prefillTicketTechs) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded text-emerald-600 focus:ring-emerald-500">
                                <span>{{ $tech }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 21. User --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        21. User (User Name)
                    </label>
                    <input
                        type="text"
                        name="user_name"
                        value="{{ old('user_name', isset($ticket) ? $ticket->reported_by : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

            </div>

            <div class="flex justify-end gap-4">
                <a
                    href="{{ isset($ticket) ? route('tickets.show', $ticket) : route('correctives.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700 shadow-md">
                    Save Service Report
                </button>
            </div>
        </form>
    </div>
</x-app-layout>