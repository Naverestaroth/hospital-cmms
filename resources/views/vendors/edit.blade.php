<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-8 text-3xl font-bold text-slate-900">
            Edit Vendor
        </h1>

        <form
            action="{{ route('vendors.update', $vendor) }}"
            method="POST"
            class="space-y-6">

            @csrf
            @method('PUT')

            {{-- Vendor Information --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <h2 class="mb-6 text-xl font-bold text-slate-900">
                    Vendor Information
                </h2>

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Vendor Code
                        </label>

                        <input
                            type="text"
                            name="vendor_code"
                            value="{{ old('vendor_code', $vendor->vendor_code) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Vendor Name
                        </label>

                        <input
                            type="text"
                            name="vendor_name"
                            value="{{ old('vendor_name', $vendor->vendor_name) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Contact Person
                        </label>

                        <input
                            type="text"
                            name="contact_person"
                            value="{{ old('contact_person', $vendor->contact_person) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $vendor->phone) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $vendor->email) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-4">

                <a
                    href="{{ route('vendors.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Save Vendor
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
