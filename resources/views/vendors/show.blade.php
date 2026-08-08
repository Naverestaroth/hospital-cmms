<x-app-layout>

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>

                <a href="{{ route('vendors.index') }}"
                    class="text-sm text-emerald-600 hover:underline">

                    ← Back to Vendors

                </a>

                <h1 class="mt-3 text-3xl font-bold text-slate-900">

                    {{ $vendor->vendor_name }}

                </h1>

                <p class="mt-2 text-slate-500">

                    Vendor Code: {{ $vendor->vendor_code }}

                </p>

            </div>

        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            <div class="lg:col-span-3 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-xl font-semibold">

                    Vendor Information

                </h2>

                <div class="mt-6 grid grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-slate-500">Vendor Code</p>

                        <p class="mt-1 font-semibold">{{ $vendor->vendor_code }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Vendor Name</p>

                        <p class="mt-1 font-semibold">{{ $vendor->vendor_name }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Contact Person</p>

                        <p class="mt-1 font-semibold">{{ $vendor->contact_person }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Phone</p>

                        <p class="mt-1 font-semibold">{{ $vendor->phone }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Email</p>

                        <p class="mt-1 font-semibold">{{ $vendor->email }}</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
