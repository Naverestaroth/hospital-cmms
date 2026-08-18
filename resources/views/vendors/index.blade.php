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
                    Vendors Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage hospital Vendors information.
                </p>
            </div>

            <a
                href="{{ route('vendors.create') }}"
                class="ds-button-primary">

                + New Vendor

            </a>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('vendors.index') }}" method="GET" class="flex gap-4">

                <input
                    type="text"
                    name="search"
                    placeholder="Search Vendor..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('vendors.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>

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
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('vendor_code') }}">Vendor Code</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('vendor_name') }}">Vendor Name</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('contact_person') }}">Contact Person</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('phone') }}">Phone</a></th>
            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('email') }}">Email</a></th>
            <th class="px-6 py-4 text-center">Action</th>
        </tr>
    </x-slot>

    @forelse($vendors as $vendor)
        <tr class="border-t border-slate-100">
            <td class="px-6 py-4">{{ $vendors->firstItem() + $loop->index }}</td>
            <td class="px-6 py-4">{{ $vendor->vendor_code }}</td>
            <td class="px-6 py-4 font-medium">{{ $vendor->vendor_name }}</td>
            <td class="px-6 py-4">{{ $vendor->contact_person }}</td>
            <td class="px-6 py-4">{{ $vendor->phone }}</td>
            <td class="px-6 py-4">{{ $vendor->email }}</td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('vendors.show', $vendor) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('vendors.edit', $vendor) }}" class="text-emerald-700 hover:underline">Edit</a>
                    <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="py-10 text-center text-slate-500">No vendor data available.</td></tr>
    @endforelse
</x-table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $vendors->links() }}
        </div>

    </div>

</x-app-layout>