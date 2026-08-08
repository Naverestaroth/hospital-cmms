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
                    Corrective Maintenance Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage corrective maintenance.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('correctives.import.upload') }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Import
                </a>

                <a
                    href="{{ route('correctives.create') }}"
                    class="ds-button-primary">

                    + New Corrective

                </a>
            </div>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('correctives.index') }}" method="GET" class="flex gap-4">

                <input
                    type="text"
                    name="search"
                    placeholder="Search corrective..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('correctives.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>

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
                            <a href="{{ sortUrl('repair_date') }}">
                                Tanggal Laporan
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">Jam Laporan</th>

                        <th class="px-6 py-4 text-left">Jam Visit</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('response_time') }}">
                                Time Response
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">Service Report Type</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('room') }}">
                                Ruangan
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

                        <th class="px-6 py-4 text-left">Tanggal Instal</th>

                        <th class="px-6 py-4 text-left">Distributor</th>

                        <th class="px-6 py-4 text-left">Pemeriksaan</th>

                        <th class="px-6 py-4 text-left">Problem / Diagnosa</th>

                        <th class="px-6 py-4 text-left">Solution</th>

                        <th class="px-6 py-4 text-left">Sparepart</th>

                        <th class="px-6 py-4 text-left">Jumlah Sparepart</th>

                        <th class="px-6 py-4 text-left">Hasil Pemeriksaan</th>

                        <th class="px-6 py-4 text-left">Teknisi</th>

                        <th class="px-6 py-4 text-left">User</th>

                        <th class="px-6 py-4 text-center">Actions</th>

                    </tr>

                </thead>

                <tbody>
                    @forelse ($correctives as $corrective)
                    <tr class="border-t">

                        <td class="px-6 py-4">
                            {{ $correctives->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->repair_date }}
                        </td>

                        <td class="px-6 py-4"> {{ $corrective->jam_laporan }}</td>

                        <td class="px-6 py-4"> {{ $corrective->jam_visit }}</td>

                        <td class="px-6 py-4">
                            {{ $corrective->response_time ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $serviceTypes = is_array($corrective->service_type) ? $corrective->service_type : [];
                            @endphp
                            {{ !empty($serviceTypes) ? implode(', ', $serviceTypes) : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->room ?? '-' }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $corrective->asset_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->brand ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->type ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->serial_number ?? '-' }}
                        </td>

                         <td class="px-6 py-4">
                            {{ $corrective->tanggal_instal ?? '-' }}
                        </td>

                         <td class="px-6 py-4">
                            {{ $corrective->distributor ?? '-' }}
                        </td>


                        <td class="px-6 py-4">
                            @php
                                $inspections = is_array($corrective->inspection) ? $corrective->inspection : [];
                            @endphp
                            {{ !empty($inspections) ? implode(', ', $inspections) : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->problem ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->solution ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->sparepart ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->quantity ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->inspection_result ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $technicians = is_array($corrective->technician) ? $corrective->technician : [];
                            @endphp
                            {{ !empty($technicians) ? implode(', ', $technicians) : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $corrective->user_name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a
                                    href="{{ route('correctives.show',$corrective) }}"
                                    class="text-blue-600 hover:underline">
                                    View
                                </a>

                                <a
                                    href="{{ route('correctives.edit',$corrective) }}"
                                    class="text-emerald-700 hover:underline">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('correctives.destroy', $corrective) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this corrective maintenance report?')">
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
                        <td colspan="22" class="py-10 text-center text-slate-500">
                            No corrective data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $correctives->links() }}
        </div>

    </div>

</x-app-layout>