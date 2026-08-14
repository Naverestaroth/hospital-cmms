<x-app-layout>

    <div class="space-y-6" x-data="{ viewMode: '{{ request('view', 'room') }}' }">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Asset Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage hospital medical and non-medical assets.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="/assets/import" class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Import
                </a>

                <a
                    href="{{ route('assets.create') }}"
                    class="ds-button-primary">
                    + New Asset
                </a>
            </div>

        </div>

        <!-- Search & View Mode Switcher -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <form action="{{ route('assets.index') }}" method="GET" class="flex-1 flex gap-4">
                <input type="hidden" name="view" :value="viewMode">

                <input
                    type="text"
                    name="search"
                    placeholder="Search asset..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('assets.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>
            </form>

            <!-- Pilihan Tampilan Switcher (Default / Per Ruangan) -->
            <div class="flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-slate-50 p-1.5 self-start md:self-auto">
                <button
                    type="button"
                    @click="viewMode = 'default'"
                    :class="viewMode === 'default' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:bg-slate-200/60'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/>
                    </svg>
                    Default
                </button>

                <button
                    type="button"
                    @click="viewMode = 'room'"
                    :class="viewMode === 'room' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:bg-slate-200/60'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                    </svg>
                    Per Ruangan
                </button>
            </div>

        </div>

        <!-- Default View Mode: Tabel Asset Standard -->
        <div x-show="viewMode === 'default'" class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    @php
                    function sortUrl($field)
                    {
                    return request()->fullUrlWithQuery([
                    'sort' => $field,
                    'direction' => request('sort') === $field && request('direction') === 'asc'
                    ? 'desc'
                    : 'asc'
                    ]);
                    }
                    @endphp

                    <tr class="border-t transition hover:bg-slate-50">

                        <th class="px-6 py-4 text-left">No</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('room') }}">
                                Room
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('asset_name') }}">
                                Asset Name
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('brand') }}">
                                Brand
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('type') }}">
                                Type
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('serial_number') }}">
                                Serial Number
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('status') }}">
                                Status
                            </a>
                        </th>

                        <th class="px-6 py-4 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>
                    @forelse ($assets as $asset)
                    <tr class="border-t">

                        <td class="px-6 py-4">
                            {{ $assets->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $asset->room ?? '-' }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $asset->asset_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ !empty($asset->brand) ? $asset->brand : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ !empty($asset->type) ? $asset->type : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ !empty($asset->serial_number) ? $asset->serial_number : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($asset->status === 'berfungsi' || $asset->status === 'Active')
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">
                                Berfungsi
                            </span>
                            @elseif ($asset->status === 'dalam perbaikan' || $asset->status === 'Maintenance')
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm text-amber-700">
                                Dalam Perbaikan
                            </span>
                            @elseif ($asset->status === 'rusak' || $asset->status === 'Broken')
                            <span class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">
                                Rusak
                            </span>
                            @elseif ($asset->status === 'proses penghapusan')
                            <span class="rounded-full bg-slate-200 px-3 py-1 text-sm text-slate-700">
                                Proses Penghapusan
                            </span>
                            @else
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                                {{ ucwords($asset->status) }}
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a
                                    href="{{ route('assets.show', $asset) }}"
                                    class="text-blue-600 hover:underline">
                                    View
                                </a>

                                <a
                                    href="{{ route('assets.edit', $asset) }}"
                                    class="text-emerald-700 hover:underline">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('assets.destroy', $asset) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this asset?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-slate-500">
                            No asset data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <!-- View Mode: Per Ruangan (Grouped by Room Accordions) -->
        <div x-show="viewMode === 'room'" class="space-y-4">
            @php
                $itemsToGroup = method_exists($assets, 'getCollection') ? $assets->getCollection() : $assets;
                $groupedAssets = $itemsToGroup->groupBy(function($item) {
                    return !empty(trim((string)$item->room)) ? trim((string)$item->room) : 'Unassigned / Ruangan Tidak Ditentukan';
                })->sortKeys();
            @endphp

            @forelse($groupedAssets as $roomName => $roomAssets)
                <div x-data="{ isOpen: true }" class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden transition">
                    
                    <!-- Room Accordion Header -->
                    <button
                        type="button"
                        @click="isOpen = !isOpen"
                        class="w-full flex items-center justify-between p-5 bg-slate-50/80 hover:bg-slate-100/80 transition text-left border-b border-slate-200/60">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                                <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">
                                    {{ $roomName }}
                                </h2>
                                <p class="text-xs text-slate-500">
                                    Location Group
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                {{ $roomAssets->count() }} {{ Str::plural('Asset', $roomAssets->count()) }}
                            </span>

                            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm transition" :class="{ 'rotate-180': isOpen }">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-600 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </div>
                        </div>

                    </button>

                    <!-- Room Assets Table (Collapsible) -->
                    <div x-show="isOpen" class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-50/50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">No</th>
                                    <th class="px-6 py-3.5 text-left">Asset Code</th>
                                    <th class="px-6 py-3.5 text-left">Asset Name</th>
                                    <th class="px-6 py-3.5 text-left">Brand</th>
                                    <th class="px-6 py-3.5 text-left">Type</th>
                                    <th class="px-6 py-3.5 text-left">Serial Number</th>
                                    <th class="px-6 py-3.5 text-left">Status</th>
                                    <th class="px-6 py-3.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($roomAssets as $index => $asset)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4 text-slate-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 font-mono font-semibold text-slate-700">
                                            {{ $asset->asset_code ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-900">
                                            {{ $asset->asset_name }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ !empty($asset->brand) ? $asset->brand : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ !empty($asset->type) ? $asset->type : '-' }}
                                        </td>
                                        <td class="px-6 py-4 font-mono text-slate-600">
                                            {{ !empty($asset->serial_number) ? $asset->serial_number : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($asset->status === 'berfungsi' || $asset->status === 'Active')
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    Berfungsi
                                                </span>
                                            @elseif ($asset->status === 'dalam perbaikan' || $asset->status === 'Maintenance')
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                    Dalam Perbaikan
                                                </span>
                                            @elseif ($asset->status === 'rusak' || $asset->status === 'Broken')
                                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                    Rusak
                                                </span>
                                            @elseif ($asset->status === 'proses penghapusan')
                                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    Proses Penghapusan
                                                </span>
                                            @else
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    {{ ucwords($asset->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-4">
                                                <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:underline">
                                                    View
                                                </a>
                                                <a href="{{ route('assets.edit', $asset) }}" class="text-emerald-700 hover:underline">
                                                    Edit
                                                </a>
                                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @empty
                <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center text-slate-500 shadow-sm">
                    No asset data available for grouping.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $assets->links() }}
        </div>

    </div>

</x-app-layout>