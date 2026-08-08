<x-app-layout>

    <div class="space-y-6">

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

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('assets.index') }}" method="GET" class="flex gap-4">

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

        <!-- Pagination -->
        <div class="mt-6">
            {{ $assets->links() }}
        </div>

    </div>

</x-app-layout>