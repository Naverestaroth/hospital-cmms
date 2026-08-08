<x-app-layout>

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>

                <a href="{{ route('spareparts.index') }}"
                    class="text-sm text-emerald-600 hover:underline">

                    ← Back to Spare Parts

                </a>

                <h1 class="mt-3 text-3xl font-bold text-slate-900">

                    {{ $sparepart->part_name }}

                </h1>

                <p class="mt-2 text-slate-500">

                    Part Code: {{ $sparepart->part_code }}

                </p>

            </div>

        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            <div class="lg:col-span-3 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-xl font-semibold">

                    Spare Part Information

                </h2>

                <div class="mt-6 grid grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-slate-500">Part Code</p>

                        <p class="mt-1 font-semibold">{{ $sparepart->part_code }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Part Name</p>

                        <p class="mt-1 font-semibold">{{ $sparepart->part_name }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Stock</p>

                        <p class="mt-1 font-semibold">{{ $sparepart->stock }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Unit</p>

                        <p class="mt-1 font-semibold">{{ $sparepart->unit }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Location</p>

                        <p class="mt-1 font-semibold">{{ $sparepart->location ?? '-' }}</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
