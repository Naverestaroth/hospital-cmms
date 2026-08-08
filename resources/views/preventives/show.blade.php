<x-app-layout>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">

            <div>

                <a href="{{ route('preventives.index') }}"
                    class="text-sm text-emerald-600 hover:underline">

                    ← Back to Preventive Maintenance

                </a>

                <h1 class="mt-3 text-3xl font-bold text-slate-900">
                    {{ $preventive->asset_name }}
                </h1>

                <p class="mt-2 text-slate-500">
                    Detailed preventive maintenance report.
                </p>

            </div>

            <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-700">

                {{ $preventive->status }}

            </span>

        </div>

        {{-- Report Information --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Report Information
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                <div>

                    <p class="text-sm text-slate-500">
                        Maintenance Date
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->schedule_date ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Room
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->room ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Asset Information --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Asset Information
            </h2>

            <div class="grid gap-6 md:grid-cols-2">

                <div>

                    <p class="text-sm text-slate-500">
                        Asset Code
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->asset_code ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Asset Name
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->asset_name ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Brand
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->brand ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Type
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->type ?? '-' }}
                    </p>

                </div>

                <div class="md:col-span-2">

                    <p class="text-sm text-slate-500">
                        Serial Number
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ $preventive->serial_number ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Preventive Checklist --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Preventive Checklist
            </h2>

            @php
                $checklists = is_array($preventive->checklist)
                    ? $preventive->checklist
                    : (json_decode($preventive->checklist ?? '[]', true) ?: []);
            @endphp

            <div class="grid gap-4 md:grid-cols-2">

                @forelse($checklists as $item)

                    <div class="rounded-2xl border border-slate-200 p-4">

                        ✓ {{ $item }}

                    </div>

                @empty

                    <span class="text-slate-500">
                        -
                    </span>

                @endforelse

            </div>

        </div>

        {{-- Equipment Inspection --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Equipment Inspection
            </h2>

            <div class="space-y-6">

                <div>

                    <p class="text-sm text-slate-500">
                        Good Condition
                    </p>

                    <div class="mt-2 rounded-2xl bg-slate-50 p-4">

                        {{ $preventive->good_condition ?? '-' }}

                    </div>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Problem Found
                    </p>

                    <div class="mt-2 rounded-2xl bg-slate-50 p-4">

                        {{ $preventive->problem_found ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

        {{-- Asset Condition --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Asset Condition
            </h2>

            <span class="rounded-full bg-blue-100 px-5 py-2 text-sm font-semibold text-blue-700">

                {{ $preventive->condition ?? '-' }}

            </span>

        </div>

        {{-- Technician --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Technician
            </h2>

            <span class="rounded-full bg-emerald-100 px-5 py-2 font-medium text-emerald-700">

                {{ $preventive->technician ?? '-' }}

            </span>

        </div>

        {{-- Status --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Maintenance Status
            </h2>

            @if($preventive->status == 'Completed')

                <span class="rounded-full bg-emerald-100 px-5 py-2 font-semibold text-emerald-700">
                    Completed
                </span>

            @elseif($preventive->status == 'Scheduled')

                <span class="rounded-full bg-yellow-100 px-5 py-2 font-semibold text-yellow-700">
                    Scheduled
                </span>

            @else

                <span class="rounded-full bg-red-100 px-5 py-2 font-semibold text-red-700">
                    {{ $preventive->status }}
                </span>

            @endif

        </div>

        {{-- Notes --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="mb-6 text-xl font-bold text-slate-900">
                Notes
            </h2>

            <div class="rounded-2xl bg-slate-50 p-5">

                {{ $preventive->notes ?? '-' }}

            </div>

        </div>

    </div>

</x-app-layout>