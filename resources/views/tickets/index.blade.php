<x-app-layout>

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            @if(session('success'))

            <div class="mb-6 rounded-xl bg-green-100 p-4 text-green-700">

                {{ session('success') }}

            </div>

            @endif

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Ticket Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage hospital medical and non-medical Ticket.
                </p>
            </div>

            <a
                href="{{ route('tickets.create') }}"
                class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700">
                + Create Ticket
            </a>


        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex gap-4">

                <input
                    type="text"
                    placeholder="Search ticket..."
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

                        <th class="px-6 py-4 text-left">Ticket ID</th>

                        <th class="px-6 py-4 text-left">Asset</th>

                        <th class="px-6 py-4 text-left">Reported By</th>

                        <th class="px-6 py-4 text-left">Priority</th>

                        <th class="px-6 py-4 text-left">Status</th>

                        <th class="px-6 py-4 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>
                    @forelse ($tickets as $ticket)
                    <tr class="border-t">
                        <td class="px-6 py-4">
                            {{ $ticket->ticket_code }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $ticket->asset->asset_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $ticket->reported_by }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $ticket->priority }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($ticket->status === 'Open')
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">
                                Open
                            </span>
                            @elseif ($ticket->status === 'In Progress')
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">
                                In Progress
                            </span>
                            @elseif ($ticket->status === 'Completed')
                            <span class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-700">
                                Completed
                            </span>
                            @else
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                                {{ $ticket->status }}
                            </span>
                            @endif
                        </td>

                        <td class="space-x-3 px-6 py-4 text-center">

                            <a
                                href="{{ route('tickets.show',$ticket) }}"
                                class="text-blue-600 hover:underline">

                                Detail

                            </a>

                            <a
                                href="{{ route('tickets.edit',$ticket) }}"
                                class="text-emerald-600 hover:underline">

                                Edit

                            </a>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-500">
                            No ticket data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>