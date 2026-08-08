<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-8 text-3xl font-bold text-slate-900">
            Edit Spare Part
        </h1>

        <form
            action="{{ route('spareparts.update', $sparepart) }}"
            method="POST"
            class="space-y-6">

            @csrf
            @method('PUT')

            {{-- Spare Part Information --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <h2 class="mb-6 text-xl font-bold text-slate-900">
                    Spare Part Information
                </h2>

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Part Code
                        </label>

                        <input
                            type="text"
                            name="part_code"
                            value="{{ old('part_code', $sparepart->part_code) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Part Name
                        </label>

                        <input
                            type="text"
                            name="part_name"
                            value="{{ old('part_name', $sparepart->part_name) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Stock
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', $sparepart->stock) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Unit
                        </label>

                        <input
                            type="text"
                            name="unit"
                            value="{{ old('unit', $sparepart->unit) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="{{ old('location', $sparepart->location) }}"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">
                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-4">

                <a
                    href="{{ route('spareparts.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Save Spare Part
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
