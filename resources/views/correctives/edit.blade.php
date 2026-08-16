@php
$roomsList = [
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

$techniciansList = (isset($techniciansList) && count($techniciansList) > 0)
    ? $techniciansList
    : \App\Models\Technician::onDuty()->orderBy('name')->pluck('name');

// Reconstruct arrays from model
$savedServiceTypes = is_array($corrective->service_type) ? $corrective->service_type : [];
$savedInspections = is_array($corrective->inspection) ? $corrective->inspection : [];
$savedTechnicians = is_array($corrective->technician) ? $corrective->technician : [];

// Determine initial asset ID for relationship/snapshot
$initialAssetId = ($corrective->ticket && $corrective->ticket->asset)
    ? $corrective->ticket->asset->id
    : (\App\Models\Asset::where('asset_code', $corrective->asset_code)->value('id') 
       ?? \App\Models\Asset::where('asset_name', $corrective->asset_name)->where('room', $corrective->room)->value('id') 
       ?? '');
@endphp

<x-app-layout>

    <div class="max-w-4xl space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Edit Service Report Elektromedis
            </h1>
            <p class="mt-2 text-slate-500">
                Official IPSRS Service Report Elektromedis form.
            </p>
        </div>

        <form action="{{ route('correctives.update', $corrective) }}" method="POST" id="corrective-form" class="space-y-6">
            @csrf
            @method('PUT')

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
            <input type="hidden" name="asset_code" id="asset_code" value="{{ old('asset_code', $corrective->asset_code) }}">

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-6">

                {{-- 1. Tanggal Laporan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        1. Tanggal Laporan
                    </label>
                    <input
                        type="date"
                        name="repair_date"
                        value="{{ old('repair_date', $corrective->repair_date ? \Carbon\Carbon::parse($corrective->repair_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 2. Jam Laporan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        2. Jam Laporan
                    </label>
                    <input
                        type="time"
                        name="jam_laporan"
                        value="{{ old('jam_laporan', $corrective->jam_laporan) }}"
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
                        value="{{ old('jam_visit', $corrective->jam_visit) }}"
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
                            <option value="{{ $response }}" {{ old('response_time', $corrective->response_time) == $response ? 'selected' : '' }}>
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
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <input
                                    type="checkbox"
                                    name="service_type[]"
                                    value="{{ $item }}"
                                    {{ in_array($item, old('service_type', $savedServiceTypes)) ? 'checked' : '' }}
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
                    <select
                        name="room"
                        id="preventive-room"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Select Room --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room }}" {{ old('room', $corrective->room) == $room ? 'selected' : '' }}>
                                {{ $room }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 7. Nama Alat --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        7. Nama Alat (Pilih Alat)
                    </label>
                    <select
                        name="asset_id"
                        id="preventive-asset"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Select Asset --</option>
                        @if ($initialAssetId)
                            <option value="{{ $initialAssetId }}" selected>
                                {{ $corrective->asset_name }}
                            </option>
                        @endif
                    </select>
                    {{-- Hidden asset name to save --}}
                    <input type="hidden" name="asset_name" id="asset_name" value="{{ old('asset_name', $corrective->asset_name) }}">
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
                        value="{{ old('brand', $corrective->brand) }}"
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
                        value="{{ old('type', $corrective->type) }}"
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
                        value="{{ old('serial_number', $corrective->serial_number) }}"
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
                        value="{{ old('tanggal_instal', $corrective->tanggal_instal) }}"
                        placeholder="e.g. 2024"
                        readonly
                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-700 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 12. Distributor --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        12. Distributor
                    </label>
                    <input
                        type="text"
                        name="distributor"
                        value="{{ old('distributor', $corrective->distributor) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 13. Pemeriksaan --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        13. Pemeriksaan
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($inspections as $item)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <input
                                    type="checkbox"
                                    name="inspection[]"
                                    value="{{ $item }}"
                                    {{ in_array($item, old('inspection', $savedInspections)) ? 'checked' : '' }}
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
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('problem', $corrective->problem) }}</textarea>
                </div>

                {{-- 15. Solution / Tindakan yang dilakukan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        15. Solution / Tindakan yang dilakukan
                    </label>
                    <textarea
                        name="solution"
                        rows="4"
                        placeholder="Deskripsikan tindakan solusi..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('solution', $corrective->solution) }}</textarea>
                </div>

                {{-- 16. Sparepart yang digunakan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        16. Sparepart yang digunakan
                    </label>
                    <input
                        type="text"
                        name="sparepart"
                        value="{{ old('sparepart', $corrective->sparepart) }}"
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
                        value="{{ old('quantity', $corrective->quantity) }}"
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
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('notes', $corrective->notes) }}</textarea>
                </div>

                {{-- 19. Hasil Pemeriksaan --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        19. Hasil Pemeriksaan
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($results as $result)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <input
                                    type="radio"
                                    name="inspection_result"
                                    value="{{ $result }}"
                                    {{ old('inspection_result', $corrective->inspection_result) == $result ? 'checked' : '' }}
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
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <input
                                    type="checkbox"
                                    name="technician[]"
                                    value="{{ $tech }}"
                                    {{ in_array($tech, old('technician', $savedTechnicians)) ? 'checked' : '' }}
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
                        value="{{ old('user_name', $corrective->user_name) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

            </div>

            <div class="flex justify-end gap-4">
                <a
                    href="{{ route('correctives.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Save Service Report
                </button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', async function () {
                    const roomSelect = document.getElementById('preventive-room');
                    const assetSelect = document.getElementById('preventive-asset');

                    const assetCodeEl = document.getElementById('asset_code');
                    const assetNameEl = document.getElementById('asset_name');
                    const brandEl = document.getElementById('brand');
                    const typeEl = document.getElementById('type');
                    const serialNumberEl = document.getElementById('serial_number');
                    const tanggalInstalEl = document.getElementById('tanggal_instal');

                    const initialRoom = roomSelect ? roomSelect.value : '';
                    const initialAssetId = "{{ $initialAssetId }}";

                    function setSnapshotFields(data) {
                        if (!data) return;
                        if (data.asset_code) assetCodeEl.value = data.asset_code;
                        if (data.asset_name) assetNameEl.value = data.asset_name;
                        if (data.brand) brandEl.value = data.brand;
                        if (data.type) typeEl.value = data.type;
                        if (data.serial_number) serialNumberEl.value = data.serial_number;
                        if (tanggalInstalEl && (data.procurement_year || data.procurementYear)) {
                            tanggalInstalEl.value = data.procurement_year || data.procurementYear;
                        }
                    }

                    function clearSnapshotFields() {
                        assetCodeEl.value = '';
                        assetNameEl.value = '';
                        brandEl.value = '';
                        typeEl.value = '';
                        serialNumberEl.value = '';
                        if (tanggalInstalEl) tanggalInstalEl.value = '';
                    }

                    function resetAssetSelect() {
                        assetSelect.innerHTML = '';
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = '-- Select Asset --';
                        assetSelect.appendChild(opt);
                    }

                    async function loadAssetsByRoom(room, selectedAssetId = null) {
                        resetAssetSelect();
                        if (!room) return;

                        try {
                            const res = await fetch(window.PREVENTIVE_ASSET_API.assetsByRoomUrl + '?room=' + encodeURIComponent(room), {
                                headers: { 'Accept': 'application/json' }
                            });
                            const json = await res.json();
                            const list = json.data || [];

                            list.forEach(function (asset) {
                                const opt = document.createElement('option');
                                opt.value = asset.id;
                                opt.textContent = asset.label;
                                if (selectedAssetId && String(asset.id) === String(selectedAssetId)) {
                                    opt.selected = true;
                                }
                                assetSelect.appendChild(opt);
                            });
                        } catch (e) {
                            console.error('Error loading assets by room:', e);
                        }
                    }

                    async function loadAssetDetail(assetId) {
                        if (!assetId) return;

                        try {
                            const detailUrl = window.PREVENTIVE_ASSET_API.assetDetailUrlTemplate.replace('__ASSET__', String(assetId));
                            const res = await fetch(detailUrl, {
                                headers: { 'Accept': 'application/json' }
                            });
                            const json = await res.json();
                            const data = json.data || json || {};
                            setSnapshotFields(data);
                        } catch (e) {
                            console.error('Error loading asset detail:', e);
                        }
                    }

                    if (roomSelect) {
                        roomSelect.addEventListener('change', async function () {
                            clearSnapshotFields();
                            await loadAssetsByRoom(roomSelect.value);
                        });
                    }

                    if (assetSelect) {
                        assetSelect.addEventListener('change', async function () {
                            await loadAssetDetail(assetSelect.value);
                        });
                    }

                    // INITIALIZATION ON PAGE LOAD:
                    if (initialRoom) {
                        await loadAssetsByRoom(initialRoom, initialAssetId);
                        const currentAssetId = assetSelect.value || initialAssetId;
                        if (currentAssetId) {
                            await loadAssetDetail(currentAssetId);
                        }
                    }
                });
            </script>
        </form>
    </div>
</x-app-layout>
