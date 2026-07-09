<x-app-layout>

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Vendor Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage hospital vendor information.
                </p>
            </div>

            <a
                href="{{ route('vendors.create') }}"
                class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">

                + Add Vendor

            </a>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex gap-4">

                <input
                    type="text"
                    placeholder="Search Vendor..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                <button
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

            </div>

        </div>

        <!-- Table -->

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th>Vendor code</th>
                        <th>Vendor Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>
                    @forelse($vendors as $vendor)

                    <tr>

                        <td>{{ $vendor->vendor_code }}</td>

                        <td>{{ $vendor->vendor_name }}</td>

                        <td>{{ $vendor->contact_person }}</td>

                        <td>{{ $vendor->phone }}</td>

                        <td>{{ $vendor->email }}</td>

                        

                        <td>

                            <a
                                href="{{ route('vendors.edit',$vendor) }}"
                                class="text-emerald-600 hover:underline">

                                Edit

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" class="py-10 text-center">

                            No sparepart data available.

                        </td>

                    </tr>

                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>