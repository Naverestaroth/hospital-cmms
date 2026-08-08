<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-6 text-3xl font-bold">

            Create Maintenance Ticket

        </h1>

        @if ($errors->any())

        <div class="mb-6 rounded-xl bg-red-100 p-4 text-red-700">

            <ul>

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('spareparts.store') }}" method="POST">

            @csrf

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Part Code
                </label>

                <input

                    type="text"

                    name="part_code"

                    value="{{ old('part_code') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    part Name
                </label>

                <input

                    type="text"

                    name="part_name"

                    value="{{ old('part_name') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    stock
                </label>

                <input

                    type="number"

                    name="stock"

                    value="{{ old('stock') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Unit
                </label>

                <input

                    type="text"

                    name="unit"

                    value="{{ old('unit') }}"

                    placeholder="pcs"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Location
                </label>

                <input

                    type="text"

                    name="location"

                    value="{{ old('location') }}"

                    placeholder="i.e ICU"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="create-button">

                <a

                    href="{{ route('spareparts.index') }}"

                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">

                    Cancel

                </a>

                <button

                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Save Vendor

                </button>

            </div>
        </form>
    </div>

</x-app-layout>