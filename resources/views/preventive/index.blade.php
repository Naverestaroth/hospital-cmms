<x-app-layout>

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Preventive Maintenance
            </h1>

            <p class="mt-2 text-slate-500">
                Schedule and monitor preventive maintenance activities.
            </p>
        </div>

        <button class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">
            + New Schedule
        </button>

    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <table class="min-w-full">

            <thead class="bg-slate-50">

                <tr>
                    <th class="px-6 py-4 text-left">Asset</th>
                    <th class="px-6 py-4 text-left">Room</th>
                    <th class="px-6 py-4 text-left">Schedule</th>
                    <th class="px-6 py-4 text-left">Technician</th>
                    <th class="px-6 py-4 text-left">Status</th>
                </tr>

            </thead>

            <tbody>

                <tr class="border-t">
                    <td class="px-6 py-4">Ventilator ICU</td>
                    <td class="px-6 py-4">ICU</td>
                    <td class="px-6 py-4">10 Jul 2026</td>
                    <td class="px-6 py-4">Ahmad</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-700">Scheduled</span>
                    </td>
                </tr>

                <tr class="border-t">
                    <td class="px-6 py-4">Generator</td>
                    <td class="px-6 py-4">Utility</td>
                    <td class="px-6 py-4">12 Jul 2026</td>
                    <td class="px-6 py-4">Rizky</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700">Completed</span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>