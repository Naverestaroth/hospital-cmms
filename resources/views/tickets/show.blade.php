<x-app-layout>

<div class="max-w-4xl">

    <h1 class="mb-8 text-3xl font-bold">
        Ticket Detail
    </h1>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow">

        <div class="grid grid-cols-2 gap-6">

            <div>
                <p class="text-slate-500">Ticket Code</p>
                <h2 class="text-xl font-semibold">
                    {{ $ticket->ticket_code }}
                </h2>
            </div>

            <div>
                <p class="text-slate-500">Status</p>
                <h2 class="text-xl font-semibold">
                    {{ $ticket->status }}
                </h2>
            </div>

            <div>
                <p class="text-slate-500">Asset</p>
                <h2 class="text-xl font-semibold">
                    {{ $ticket->asset->asset_name }}
                </h2>
            </div>

            <div>
                <p class="text-slate-500">Priority</p>
                <h2 class="text-xl font-semibold">
                    {{ $ticket->priority }}
                </h2>
            </div>

            <div>
                <p class="text-slate-500">Reported By</p>
                <h2 class="text-xl font-semibold">
                    {{ $ticket->reported_by }}
                </h2>
            </div>

            <div>
                <p class="text-slate-500">Created At</p>
                <h2 class="text-xl font-semibold">
                    {{ $ticket->created_at->format('d M Y H:i') }}
                </h2>
            </div>

        </div>

        <div class="mt-8">

            <p class="mb-2 text-slate-500">
                Issue
            </p>

            <div class="rounded-2xl bg-slate-100 p-5">

                {{ $ticket->issue }}

            </div>

        </div>

        <div class="mt-8">

            <a
                href="{{ route('tickets.index') }}"
                class="rounded-xl bg-slate-700 px-6 py-3 text-white hover:bg-slate-800">

                ← Back

            </a>

        </div>

    </div>

</div>

</x-app-layout>