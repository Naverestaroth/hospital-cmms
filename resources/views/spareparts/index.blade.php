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
                    Spareparts
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage hospital sparepart inventory.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('spareparts.import.upload') }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Import
                </a>

                <a
                    href="{{ route('spareparts.create') }}"
                    class="ds-button-primary">

                    + New Sparepart

                </a>
            </div>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('spareparts.index') }}" method="GET" class="flex gap-4">

                <input
                    type="text"
                    name="search"
                    placeholder="Search sparepart..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('spareparts.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>

            </form>

        </div>

        <!-- Table -->

<x-table class="rounded-3xl border border-slate-200">
    @php
        function sortUrl($field) {
            return request()->fullUrlWithQuery([
                'sort' => $field,
                'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc',
            ]);
        }
    @endphp
    <x-slot name="thead">
        <tr class="border-t transition hover:bg-slate-50">
            <th class="px-6 py-4 text-left">No</th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('part_code') }}">Code</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('part_name') }}">Name</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('stock') }}">Stock</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('unit') }}">Unit</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('location') }}">Location</a></th>
            <th class="px-6 py-4 text-center">Action</th>
        </tr>
    </x-slot>

    @forelse($spareparts as $sparepart)
        <tr class="border-t border-slate-100">
            <td class="px-6 py-4">{{ $spareparts->firstItem() + $loop->index }}</td>
            <td class="px-6 py-4">{{ $sparepart->part_code }}</td>
            <td class="px-6 py-4 font-medium">{{ $sparepart->part_name }}</td>
            <td class="px-6 py-4">{{ $sparepart->stock }}</td>
            <td class="px-6 py-4">{{ $sparepart->unit }}</td>
            <td class="px-6 py-4">{{ $sparepart->location }}</td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('spareparts.show', $sparepart) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('spareparts.edit', $sparepart) }}" class="text-emerald-700 hover:underline">Edit</a>
                    <form action="{{ route('spareparts.destroy', $sparepart) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this sparepart?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="py-10 text-center text-slate-500">No sparepart data available.</td></tr>
    @endforelse
</x-table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $spareparts->links() }}
        </div>

    </div>

</x-app-layout>