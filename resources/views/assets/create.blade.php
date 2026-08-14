<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-8 text-3xl font-bold text-slate-900">
            Add New Asset
        </h1>

        <form
            action="{{ route('assets.store') }}"
            method="POST"
            class="space-y-6">

            @csrf

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

            {{-- 1. Location (Room) --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <h2 class="mb-6 text-xl font-bold text-slate-900">
                    Location
                </h2>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Room
                </label>

                <select
                    name="room"
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                    <option value="">-- Select Room --</option>

                    @foreach($rooms as $room)
                    <option
                        value="{{ $room }}"
                        {{ old('room') == $room ? 'selected' : '' }}>
                        {{ $room }}
                    </option>
                    @endforeach

                </select>

            </div>

            {{-- 2. Asset Information --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <h2 class="mb-6 text-xl font-bold text-slate-900">
                    Asset Information
                </h2>

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Asset Code
                        </label>

                        <input
                            type="text"
                            name="asset_code"
                            value="{{ old('asset_code') }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Asset Name
                        </label>

                        <input
                            type="text"
                            name="asset_name"
                            value="{{ old('asset_name') }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Brand
                        </label>

                        <input
                            type="text"
                            name="brand"
                            value="{{ old('brand') }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Type
                        </label>

                        <input
                            type="text"
                            name="type"
                            value="{{ old('type') }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Serial Number
                        </label>

                        <input
                            type="text"
                            name="serial_number"
                            value="{{ old('serial_number') }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Procurement Year
                        </label>

                        <input
                            type="text"
                            name="procurement_year"
                            value="{{ old('procurement_year') }}"
                            placeholder="Tahun"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                </div>

            </div>

            {{-- 3. Asset Status & Additional Information --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-6">

                <h2 class="text-xl font-bold text-slate-900">
                    Asset Status
                </h2>

                @php
                    $selectedStatus = old('status_select', old('status'));
                @endphp

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        name="status_select"
                        id="asset-status-select"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                        <option value="">-- Select Status --</option>
                        <option value="berfungsi" {{ $selectedStatus === 'berfungsi' ? 'selected' : '' }}>Berfungsi</option>
                        <option value="dalam perbaikan" {{ $selectedStatus === 'dalam perbaikan' ? 'selected' : '' }}>dalam perbaikan</option>
                        <option value="rusak" {{ $selectedStatus === 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="proses penghapusan" {{ $selectedStatus === 'proses penghapusan' ? 'selected' : '' }}>Proses Penghapusan</option>
                        <option value="Other" {{ $selectedStatus === 'Other' ? 'selected' : '' }}>Other</option>

                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Description / Lain-lain
                    </label>

                    <textarea
                        name="description"
                        rows="3"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('description') }}</textarea>
                </div>

            </div>


            <div class="flex justify-end gap-4">

                <a
                    href="{{ route('assets.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">

                    Save Asset

                </button>

            </div>

        </form>

    </div>

</x-app-layout>