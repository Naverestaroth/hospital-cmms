<x-app-layout>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">

            <div>

                <a href="{{ route('correctives.index') }}"
                    class="text-sm text-emerald-600 hover:underline">

                    ← Back to Corrective Maintenance

                </a>

                <h1 class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $corrective->asset_name }}
                </h1>

                <p class="mt-2 text-slate-500">
                    Detail of corrective maintenance activity.
                </p>

            </div>

            <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-700">

                {{ $corrective->status }}

            </span>

        </div>

        {{-- Report Information --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Report Information
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                <div>
                    <p class="text-sm text-slate-500">Repair Date</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->repair_date ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Response Time</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->response_time ?? '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-slate-500">Room</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->room ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Asset Information --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Asset Information
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                <div>
                    <p class="text-sm text-slate-500">Asset Code</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->asset_code ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Asset Name</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->asset_name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Brand</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->brand ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Type</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->type ?? '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-slate-500">Serial Number</p>
                    <p class="mt-1 font-semibold">
                        {{ $corrective->serial_number ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Service Report --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Service Report Type
            </h2>

            <div class="flex flex-wrap gap-3">

                @php
                $serviceTypes = is_array($corrective->service_type)
                ? $corrective->service_type
                : (json_decode($corrective->service_type ?? '[]', true) ?: []);
                @endphp

                @forelse($serviceTypes as $item)

                <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-700">
                    {{ $item }}
                </span>

                @empty

                <span class="text-slate-500">-</span>

                @endforelse

               

            </div>

        </div>

        {{-- Inspection --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Inspection Checklist
            </h2>

            <div class="grid gap-3 md:grid-cols-2">

                @php
                $inspections = is_array($corrective->inspection)
                ? $corrective->inspection
                : (json_decode($corrective->inspection ?? '[]', true) ?: []);
                @endphp

                @forelse($inspections as $item)

                <div class="rounded-2xl border border-slate-200 p-4">
                    ✓ {{ $item }}
                </div>

                @empty

                <span class="text-slate-500">-</span>

                @endforelse


            </div>

        </div>

        {{-- Repair Information --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Repair Information
            </h2>

            <div class="space-y-6">

                <div>

                    <p class="text-sm text-slate-500">
                        Problem / Diagnosis
                    </p>

                    <div class="mt-2 rounded-2xl bg-slate-50 p-4">

                        {{ $corrective->problem ?? '-' }}

                    </div>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Solution / Action
                    </p>

                    <div class="mt-2 rounded-2xl bg-slate-50 p-4">

                        {{ $corrective->solution ?? '-' }}

                    </div>

                </div>

                <div class="grid gap-6 md:grid-cols-2">

                    <div>

                        <p class="text-sm text-slate-500">
                            Sparepart
                        </p>

                        <p class="mt-1 font-semibold">
                            {{ $corrective->sparepart ?? '-' }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Quantity
                        </p>

                        <p class="mt-1 font-semibold">
                            {{ $corrective->quantity ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- Result --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Inspection Result
            </h2>

            <span class="rounded-full bg-blue-100 px-5 py-2 text-sm font-semibold text-blue-700">

                {{ $corrective->inspection_result ?? '-' }}

            </span>

        </div>

        {{-- Technician --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Technician
            </h2>

            <div class="flex flex-wrap gap-3">

                @php
                $technicians = is_array($corrective->technician)
                ? $corrective->technician
                : (json_decode($corrective->technician ?? '[]', true) ?: []);
                @endphp

                @forelse($technicians as $tech)

                <span class="rounded-full bg-slate-100 px-4 py-2">
                    {{ $tech }}
                </span>

                @empty

                <span>-</span>

                @endforelse


            </div>

        </div>

        {{-- User Confirmation --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                User Confirmation
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                <div>

                    <p class="text-sm text-slate-500">
                        User Name
                    </p>

                    <p class="mt-1 font-semibold">

                        {{ $corrective->user_name ?? '-' }}

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Position
                    </p>

                    <p class="mt-1 font-semibold">

                        {{ $corrective->position ?? '-' }}

                    </p>

                </div>

            </div>

        </div>

        {{-- Notes --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold">
                Notes
            </h2>

            <div class="rounded-2xl bg-slate-50 p-5">

                {{ $corrective->notes ?? '-' }}

            </div>

        </div>

    </div>

</x-app-layout>