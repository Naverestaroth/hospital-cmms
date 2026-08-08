<x-app-layout>

    <div class="space-y-6">

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

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('preventives.index') }}" method="GET" class="flex gap-4">

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

        </div>

        <!-- Table -->

        <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    @php
                    function sortUrl($field)
                    {
                    return request()->fullUrlWithQuery([
                    'sort' => $field,
                    'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc'
                    ]);
                    }
                    @endphp

                    <tr class="border-t transition hover:bg-slate-50">

                        <th class="px-6 py-4 text-left">No</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('room') }}">
                                Ruang
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('schedule_date') }}">
                                Tanggal
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('asset_name') }}">
                                Nama Alat
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('brand') }}">
                                Merk
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

                        <th class="px-6 py-4 text-left">Checklist Pemeliharaan Preventive</th>

                        <th class="px-6 py-4 text-left">Pengecekkan dalam Kondisi Baik</th>

                        <th class="px-6 py-4 text-left">Pengecekkan dalam Kondisi Rusak</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('status') }}">
                                Kondisi Alat
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">Keterangan</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('technician') }}">
                                Engineer
                            </a>
                        </th>

                        <th class="px-6 py-4 text-center">Actions</th>

                    </tr>

                </thead>

                <tbody>
                    @forelse ($preventives as $preventive)
                    <tr class="border-t">

                        <td class="px-6 py-4">
                            {{ $preventives->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->room }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->schedule_date }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $preventive->asset_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->brand ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->type ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->serial_number ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                            $checklists = is_array($preventive->checklist)
                            ? $preventive->checklist
                            : (json_decode($preventive->checklist ?? '[]', true) ?: []);
                            @endphp
                            {{ !empty($checklists) ? implode(', ', $checklists) : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->good_condition ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->problem_found ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($preventive->condition === 'Baik')
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">
                                Baik
                            </span>

                            @elseif ($preventive->condition === 'Berfungsi Tidak Sempurna')
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">
                                Berfungsi Tidak Sempurna
                            </span>

                            @elseif ($preventive->condition === 'Perlu Perbaikan')
                            <span class="rounded-full bg-orange-100 px-3 py-1 text-sm text-orange-700">
                                Perlu Perbaikan
                            </span>

                            @elseif ($preventive->condition === 'Tidak Berfungsi')
                            <span class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">
                                Tidak Berfungsi
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->notes ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->technician ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a
                                    href="{{ route('preventives.show', $preventive->id) }}"
                                    class="text-blue-600 hover:underline">
                                    View
                                </a>

                                <a
                                    href="{{ route('preventives.edit', $preventive) }}"
                                    class="text-emerald-700 hover:underline">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('preventives.destroy', $preventive) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this preventive schedule?')">
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
                        <td colspan="14" class="py-10 text-center text-slate-500">
                            No preventive maintenance schedules available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $preventives->links() }}
        </div>

    </div>

</x-app-layout>