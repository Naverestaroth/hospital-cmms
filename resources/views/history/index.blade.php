<x-app-layout>

    <div class="space-y-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Maintenance History
            </h1>

            <p class="mt-2 text-slate-500">
                View preventive and corrective maintenance history.
            </p>

        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b px-6 py-4">

                <h2 class="text-xl font-semibold">

                    Preventive Maintenance

                </h2>

            </div>

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left">Asset</th>

                        <th class="px-6 py-4 text-left">Schedule Date</th>

                        <th class="px-6 py-4 text-left">Technician</th>

                        <th class="px-6 py-4 text-left">Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($preventives as $preventive)

                    <tr class="border-t">

                        <td class="px-6 py-4">

                            {{ $preventive->asset->asset_name }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $preventive->schedule_date }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $preventive->technician }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $preventive->status }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="py-8 text-center">

                            No preventive history.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b px-6 py-4">

                <h2 class="text-xl font-semibold">

                    Corrective Maintenance

                </h2>

            </div>

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left">Ticket</th>

                        <th class="px-6 py-4 text-left">Asset</th>

                        <th class="px-6 py-4 text-left">Technician</th>

                        <th class="px-6 py-4 text-left">Repair Date</th>

                        <th class="px-6 py-4 text-left">Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($correctives as $corrective)

                    <tr class="border-t">

                        <td class="px-6 py-4">

                            {{ $corrective->ticket->ticket_code }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $corrective->ticket->asset->asset_name }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $corrective->technician }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $corrective->repair_date }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $corrective->status }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="py-8 text-center">

                            No corrective history.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>