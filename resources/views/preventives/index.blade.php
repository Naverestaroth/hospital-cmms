<x-app-layout>

    <div class="space-y-6" x-data="{ viewMode: '{{ request('view', 'default') }}' }">

        @if(session('success'))
        <div class="rounded-xl bg-green-100 p-4 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-red-100 p-4 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Preventive Maintenance
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage Scheduled Preventive Maintenance.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('preventives.import.upload') }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Import
                </a>

                <a
                    href="{{ route('preventives.create') }}"
                    class="ds-button-primary">
                    + New Preventive
                </a>
            </div>

        </div>

        <!-- Search & View Mode Switcher -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <form action="{{ route('preventives.index') }}" method="GET" class="flex-1 flex gap-4">
                <input type="hidden" name="view" :value="viewMode">

                <input
                    type="text"
                    name="search"
                    placeholder="Search maintenance..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('preventives.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>
            </form>

            <!-- View Mode Switcher -->
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

        <!-- =================== DEFAULT VIEW =================== -->
        <div x-show="viewMode === 'default'">
            <x-table class="rounded-3xl border border-slate-200">
                @php
                    if (!function_exists('sortUrl')) {
                        function sortUrl($field) {
                            return request()->fullUrlWithQuery([
                                'sort'      => $field,
                                'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc',
                            ]);
                        }
                    }
                @endphp
                <x-slot name="thead">
                    <tr class="border-t transition hover:bg-slate-50">
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('room') }}">Ruang</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('schedule_date') }}">Tanggal</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('asset_name') }}">Nama Alat</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('brand') }}">Merk</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('type') }}">Type</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('serial_number') }}">Serial Number</a></th>
                        <th class="px-6 py-4 text-left">Checklist Pemeliharaan Preventive</th>
                        <th class="px-6 py-4 text-left">Pengecekkan dalam Kondisi Baik</th>
                        <th class="px-6 py-4 text-left">Pengecekkan dalam Kondisi Rusak</th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('status') }}">Kondisi Alat</a></th>
                        <th class="px-6 py-4 text-left">Keterangan</th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('technician') }}">Engineer</a></th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </x-slot>

                @forelse ($preventives as $preventive)
                    <tr class="border-t">
                        <td class="px-6 py-4">{{ $preventives->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">{{ $preventive->room }}</td>
                        <td class="px-6 py-4">{{ $preventive->schedule_date }}</td>
                        <td class="px-6 py-4 font-medium">{{ $preventive->asset_name }}</td>
                        <td class="px-6 py-4">{{ $preventive->brand ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $preventive->type ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $preventive->serial_number ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                            $checklists = is_array($preventive->checklist) ? $preventive->checklist : (json_decode($preventive->checklist ?? '[]', true) ?: []);
                            @endphp
                            {{ !empty($checklists) ? implode(', ', $checklists) : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $preventive->good_condition ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $preventive->problem_found ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if ($preventive->condition === 'Baik')
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">Baik</span>
                            @elseif ($preventive->condition === 'Berfungsi Tidak Sempurna')
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">Berfungsi Tidak Sempurna</span>
                            @elseif ($preventive->condition === 'Perlu Perbaikan')
                                <span class="rounded-full bg-orange-100 px-3 py-1 text-sm text-orange-700">Perlu Perbaikan</span>
                            @elseif ($preventive->condition === 'Tidak Berfungsi')
                                <span class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">Tidak Berfungsi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $preventive->notes ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $preventive->technician ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a href="{{ route('preventives.show', $preventive->id) }}" class="text-blue-600 hover:underline">View</a>
                                <a href="{{ route('preventives.edit', $preventive) }}" class="text-emerald-700 hover:underline">Edit</a>
                                <form action="{{ route('preventives.destroy', $preventive) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this preventive schedule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="py-10 text-center text-slate-500">No preventive maintenance schedules available.</td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        <!-- =================== PER RUANGAN VIEW =================== -->
        @php
            $itemsToGroup  = method_exists($preventives, 'getCollection') ? $preventives->getCollection() : $preventives;
            $groupedItems  = $itemsToGroup->groupBy(function ($item) {
                return !empty(trim((string)$item->room)) ? trim((string)$item->room) : 'Unassigned / Ruangan Tidak Ditentukan';
            })->sortKeys();
            $firstRoomSlug = $groupedItems->keys()->isNotEmpty() ? Str::slug($groupedItems->keys()->first()) : '';
        @endphp

        <div x-show="viewMode === 'room'" class="space-y-4" x-data="{
            activeRoom: window.location.hash.startsWith('#room-') ? window.location.hash.substring(6) : '{{ $firstRoomSlug }}',
            canScrollLeft: false,
            canScrollRight: false,
            checkScroll() {
                const el = this.$refs.scrollContainer;
                if (!el) return;
                const tolerance = 2;
                this.canScrollLeft  = el.scrollLeft > tolerance;
                this.canScrollRight = el.scrollLeft + el.clientWidth < el.scrollWidth - tolerance;
            }
        }" x-init="
            $nextTick(() => checkScroll());
            window.addEventListener('resize', () => checkScroll());
            window.addEventListener('hashchange', () => {
                if (window.location.hash.startsWith('#room-')) {
                    activeRoom = window.location.hash.substring(6);
                }
            });
        ">

            <!-- Liquid Glass Room Navigation -->
            <div class="relative w-full overflow-hidden py-1 mb-1">
                <div class="mx-auto max-w-[1500px]">
                    <div class="relative overflow-hidden rounded-[40px] border border-white bg-white/35 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur-[30px] ring-1 ring-black/5">

                        <div class="absolute inset-0 bg-gradient-to-r from-white/60 via-white/20 to-white/5 opacity-70 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-b from-white/40 to-transparent opacity-50 pointer-events-none"></div>

                        <div x-show="canScrollLeft" x-transition.opacity.duration.300ms class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-white/90 via-white/70 to-transparent z-10 pointer-events-none rounded-l-[40px]"></div>
                        <div x-show="canScrollRight" x-transition.opacity.duration.300ms class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-white/90 via-white/70 to-transparent z-10 pointer-events-none rounded-r-[40px]"></div>

                        <div x-ref="scrollContainer" @scroll.passive="checkScroll" class="relative flex items-center gap-2 sm:gap-3 px-4 py-2 overflow-x-auto scroll-smooth z-0 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">

                            @forelse($roomPages ?? [] as $roomName => $pageNum)
                                @php $roomSlug = Str::slug($roomName); @endphp
                                <a
                                    href="{{ request()->fullUrlWithQuery(['page' => $pageNum, 'view' => 'room']) }}#room-{{ $roomSlug }}"
                                    class="relative whitespace-nowrap px-4 py-1.5 rounded-full text-[14px] sm:text-[15px] lg:text-[16px] font-medium tracking-tight transition-all duration-300 outline-none select-none flex-shrink-0"
                                    :class="activeRoom === '{{ $roomSlug }}' ? 'text-slate-900 shadow-sm ring-1 ring-black/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/40'"
                                    @click="activeRoom = '{{ $roomSlug }}'">

                                    <div x-show="activeRoom === '{{ $roomSlug }}'"
                                         x-transition.opacity
                                         class="absolute inset-0 bg-white/95 rounded-full shadow-[inset_0_1px_3px_rgba(255,255,255,1)] pointer-events-none -z-10"></div>

                                    {{ $roomName }}
                                </a>
                            @empty
                                <div class="px-5 py-2 text-slate-500 text-sm">No rooms available</div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Accordions -->
            @forelse($groupedItems as $roomName => $roomItems)
                <div id="room-{{ Str::slug($roomName) }}" x-data="{ isOpen: true }" class="relative rounded-[28px] border border-white/[0.35] bg-white/[0.08] shadow-[0_12px_28px_rgba(15,23,42,0.06)] backdrop-blur-[30px] overflow-hidden transition scroll-mt-24">

                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-50 pointer-events-none"></div>

                    <!-- Room Accordion Header -->
                    <button
                        type="button"
                        @click="isOpen = !isOpen"
                        class="relative z-10 w-full flex items-center justify-between px-6 py-5 bg-white/[0.02] hover:bg-white/[0.06] transition text-left border-b border-white/[0.15]">

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">{{ $roomName }}</h2>
                                <p class="text-xs text-slate-500">Preventive Maintenance Records</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                {{ $roomItems->count() }} {{ Str::plural('Record', $roomItems->count()) }}
                            </span>
                            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm" :class="{ 'rotate-180': isOpen }">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </div>
                        </div>

                    </button>

                    <!-- Room Table -->
                    <div x-show="isOpen" class="relative z-10 overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-white/[0.04] text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-white/[0.15]">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">No</th>
                                    <th class="px-6 py-3.5 text-left">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left">Nama Alat</th>
                                    <th class="px-6 py-3.5 text-left">Merk</th>
                                    <th class="px-6 py-3.5 text-left">Type</th>
                                    <th class="px-6 py-3.5 text-left">Serial Number</th>
                                    <th class="px-6 py-3.5 text-left">Checklist</th>
                                    <th class="px-6 py-3.5 text-left">Kondisi Baik</th>
                                    <th class="px-6 py-3.5 text-left">Kondisi Rusak</th>
                                    <th class="px-6 py-3.5 text-left">Kondisi Alat</th>
                                    <th class="px-6 py-3.5 text-left">Keterangan</th>
                                    <th class="px-6 py-3.5 text-left">Engineer</th>
                                    <th class="px-6 py-3.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($roomItems as $index => $preventive)
                                    <tr class="bg-white hover:bg-slate-50 transition text-slate-900">
                                        <td class="px-6 py-4 text-slate-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $preventive->schedule_date }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $preventive->asset_name }}</td>
                                        <td class="px-6 py-4">{{ $preventive->brand ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $preventive->type ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $preventive->serial_number ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                            $cls = is_array($preventive->checklist) ? $preventive->checklist : (json_decode($preventive->checklist ?? '[]', true) ?: []);
                                            @endphp
                                            {{ !empty($cls) ? implode(', ', $cls) : '-' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $preventive->good_condition ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $preventive->problem_found ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if ($preventive->condition === 'Baik')
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs text-emerald-700 font-semibold">Baik</span>
                                            @elseif ($preventive->condition === 'Berfungsi Tidak Sempurna')
                                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs text-yellow-700 font-semibold">Berfungsi Tidak Sempurna</span>
                                            @elseif ($preventive->condition === 'Perlu Perbaikan')
                                                <span class="rounded-full bg-orange-100 px-3 py-1 text-xs text-orange-700 font-semibold">Perlu Perbaikan</span>
                                            @elseif ($preventive->condition === 'Tidak Berfungsi')
                                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700 font-semibold">Tidak Berfungsi</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $preventive->notes ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $preventive->technician ?? '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-4">
                                                <a href="{{ route('preventives.show', $preventive->id) }}" class="text-blue-600 hover:underline">View</a>
                                                <a href="{{ route('preventives.edit', $preventive) }}" class="text-emerald-700 hover:underline">Edit</a>
                                                <form action="{{ route('preventives.destroy', $preventive) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this preventive schedule?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
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
                    No preventive data available for grouping.
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $preventives->links() }}
        </div>

    </div>

</x-app-layout>