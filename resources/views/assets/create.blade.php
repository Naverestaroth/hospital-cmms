<x-app-layout>

<div class="max-w-4xl">

    <h1 class="mb-8 text-3xl font-bold text-slate-900">
        Add New Asset
    </h1>

    <form
        action="{{ route('assets.store') }}"
        method="POST"
        class="space-y-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        @csrf

        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <label class="mb-2 block font-medium">
                    Asset Code
                </label>

                <input
                    type="text"
                    name="asset_code"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Asset Name
                </label>

                <input
                    type="text"
                    name="asset_name"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Category
                </label>

                <select
                    name="category"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option>Medical</option>
                    <option>Non Medical</option>

                </select>

            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Room
                </label>

                <input
                    type="text"
                    name="room"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Brand
                </label>

                <input
                    type="text"
                    name="brand"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Model
                </label>

                <input
                    type="text"
                    name="model"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Serial Number
                </label>

                <input
                    type="text"
                    name="serial_number"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Purchase Date
                </label>

                <input
                    type="date"
                    name="purchase_date"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">
            </div>

        </div>

        <div>

            <label class="mb-2 block font-medium">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>

        </div>

        <input
            type="hidden"
            name="status"
            value="Active">

        <div class="flex justify-end gap-4">

            <a
                href="{{ route('assets.index') }}"
                class="rounded-xl border border-slate-300 px-6 py-3">
                Cancel
            </a>

            <button
                class="rounded-xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">

                Save Asset

            </button>

        </div>

    </form>

</div>

</x-app-layout>