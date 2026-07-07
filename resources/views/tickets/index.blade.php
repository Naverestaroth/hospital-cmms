<x-app-layout>

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Ticket Management
            </h1>

            <p class="mt-2 text-slate-500">
                Manage maintenance requests from every hospital unit.
            </p>
        </div>

        <button
            class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">
            + Create Ticket
        </button>

    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <table class="min-w-full">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left">Ticket ID</th>
                    <th class="px-6 py-4 text-left">Asset</th>
                    <th class="px-6 py-4 text-left">Reported By</th>
                    <th class="px-6 py-4 text-left">Priority</th>
                    <th class="px-6 py-4 text-left">Status</th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">
                    <td class="px-6 py-4">TK-001</td>
                    <td class="px-6 py-4">Ventilator ICU</td>
                    <td class="px-6 py-4">ICU</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-red-100 px-3 py-1 text-red-700">High</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-yellow-700">In Progress</span>
                    </td>
                </tr>

                <tr class="border-t">
                    <td class="px-6 py-4">TK-002</td>
                    <td class="px-6 py-4">Patient Monitor</td>
                    <td class="px-6 py-4">NICU</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-orange-700">Medium</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700">Completed</span>
                    </td>
                </tr>

                <tr class="border-t">
                    <td class="px-6 py-4">TK-003</td>
                    <td class="px-6 py-4">Generator</td>
                    <td class="px-6 py-4">Utility</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-700">Low</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-red-100 px-3 py-1 text-red-700">Open</span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>