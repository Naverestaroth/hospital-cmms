@php
$technicians = [
    'Susanto',
    'Hutami',
    'Zaky',
    'Lisa',
    'Syarif',
    'Syiefa',
    'Ghazali',
];

$checklists = [
    'Pembersihan / Cleaning',
    'Pelumasan / Lubricating',
    'Penyetelan / Adjustment',
    'Penggantian Komponen / Replace',
];

$conditions = [
    'Baik',
    'Berfungsi Tidak Sempurna',
    'Perlu Perbaikan',
    'Tidak Berfungsi',
];

// Reconstruct checklist from model
$savedChecklist = is_array($preventive->checklist)
    ? $preventive->checklist
    : (json_decode($preventive->checklist ?? '[]', true) ?: []);

// Determine initial asset ID for relationship/snapshot
$initialAssetId = $preventive->asset
    ? $preventive->asset->id
    : (\App\Models\Asset::where('asset_code', $preventive->asset_code)->value('id') 
       ?? \App\Models\Asset::where('asset_name', $preventive->asset_name)->where('room', $preventive->room)->value('id') 
       ?? '');
@endphp

<x-app-layout>

    <div class="max-w-4xl space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Edit Preventive Maintenance Report
            </h1>
            <p class="mt-2 text-slate-500">
                Official IPSRS Preventive Maintenance Report form.
            </p>
        </div>

        <form action="{{ route('preventives.update', $preventive) }}" method="POST" id="preventive-form" class="space-y-6">
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

            {{-- Hidden Snapshot inputs to store data behind the scenes --}}
            <input type="hidden" name="asset_code" id="asset_code" value="{{ old('asset_code', $preventive->asset_code) }}">
            <input type="hidden" name="asset_name" id="asset_name" value="{{ old('asset_name', $preventive->asset_name) }}">

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-6">

                {{-- 1. Ruang --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        1. Ruang
                    </label>
                    <select
                        name="room"
                        id="preventive-room"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Select Room --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room }}" {{ old('room', $preventive->room) == $room ? 'selected' : '' }}>
                                {{ $room }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Tanggal --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        2. Tanggal
                    </label>
                    <input
                        type="date"
                        name="schedule_date"
                        value="{{ old('schedule_date', $preventive->schedule_date ? \Carbon\Carbon::parse($preventive->schedule_date)->format('Y-m-d') : '') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 3. Nama Alat --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        3. Nama Alat (Pilih Alat)
                    </label>
                    <select
                        name="asset_id"
                        id="preventive-asset"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Select Asset --</option>
                        @if ($preventive->asset)
                            <option value="{{ $preventive->asset->id }}" selected>
                                {{ $preventive->asset_name }}
                            </option>
                        @elseif ($initialAssetId)
                            <option value="{{ $initialAssetId }}" selected>
                                {{ $preventive->asset_name }}
                            </option>
                        @endif
                    </select>
                </div>

                {{-- 4. Merk --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        4. Merk
                    </label>
                    <input
                        type="text"
                        name="brand"
                        id="brand"
                        value="{{ old('brand', $preventive->brand) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 5. Type --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        5. Type
                    </label>
                    <input
                        type="text"
                        name="type"
                        id="type"
                        value="{{ old('type', $preventive->type) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 6. Serial Number --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        6. Serial Number
                    </label>
                    <input
                        type="text"
                        name="serial_number"
                        id="serial_number"
                        value="{{ old('serial_number', $preventive->serial_number) }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- Installation Year --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Installation Year
                    </label>
                    <input
                        type="text"
                        name="procurement_year"
                        id="procurement_year"
                        value="{{ old('procurement_year', $preventive->procurement_year) }}"
                        placeholder="e.g. 2024"
                        readonly
                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-700 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- 7. Checklist Pemeliharaan Preventive --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        7. Checklist Pemeliharaan Preventive
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($checklists as $item)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <input
                                    type="checkbox"
                                    name="checklist[]"
                                    value="{{ $item }}"
                                    {{ in_array($item, old('checklist', $savedChecklist)) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-slate-300 text-emerald-600">
                                <span>{{ $item }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 8. Pengecekkan dalam Kondisi Baik --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        8. Pengecekkan dalam Kondisi Baik
                    </label>
                    <textarea
                        name="good_condition"
                        rows="4"
                        placeholder="Deskripsikan kondisi baik alat..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('good_condition', $preventive->good_condition ?? '') }}</textarea>
                </div>

                {{-- 9. Pengecekkan dalam Kondisi Rusak --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        9. Pengecekkan dalam Kondisi Rusak
                    </label>
                    <textarea
                        name="problem_found"
                        rows="4"
                        placeholder="Deskripsikan masalah / kerusakan alat..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('problem_found', $preventive->problem_found ?? '') }}</textarea>
                </div>

                {{-- 10. Kondisi Alat --}}
                <div>
                    <label class="mb-4 block text-sm font-medium text-slate-700">
                        10. Kondisi Alat
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($conditions as $condition)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <input
                                    type="radio"
                                    name="condition"
                                    value="{{ $condition }}"
                                    {{ old('condition', $preventive->condition ?? '') == $condition ? 'checked' : '' }}
                                    class="h-5 w-5 text-emerald-600 focus:ring-emerald-500">
                                <span>{{ $condition }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 11. Keterangan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        11. Keterangan
                    </label>
                    <textarea
                        name="notes"
                        rows="4"
                        placeholder="Tambahkan keterangan tambahan jika ada..."
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('notes', $preventive->notes) }}</textarea>
                </div>

                {{-- 12. Engineer --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        12. Engineer
                    </label>
                    <select
                        name="technician"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <option value="">Select Technician</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech }}" {{ old('technician', $preventive->technician) == $tech ? 'selected' : '' }}>
                                {{ $tech }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-4">
                <a
                    href="{{ route('preventives.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Save Report
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
                    const procurementYearEl = document.getElementById('procurement_year');

                    const initialRoom = roomSelect ? roomSelect.value : '';
                    const initialAssetId = "{{ $initialAssetId }}";

                    function setSnapshotFields(data) {
                        if (!data) return;
                        if (data.asset_code) assetCodeEl.value = data.asset_code;
                        if (data.asset_name) assetNameEl.value = data.asset_name;
                        if (data.brand) brandEl.value = data.brand;
                        if (data.type) typeEl.value = data.type;
                        if (data.serial_number) serialNumberEl.value = data.serial_number;
                        if (data.procurement_year || data.procurementYear) {
                            procurementYearEl.value = data.procurement_year || data.procurementYear;
                        }
                    }

                    function clearSnapshotFields() {
                        assetCodeEl.value = '';
                        assetNameEl.value = '';
                        brandEl.value = '';
                        typeEl.value = '';
                        serialNumberEl.value = '';
                        procurementYearEl.value = '';
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

                            if (data && data.procurement_year == null && data.procurementYear != null) {
                                data.procurement_year = data.procurementYear;
                            }
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
