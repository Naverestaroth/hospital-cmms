<x-app-layout>

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Asset Management
            </h1>

            <p class="mt-2 text-slate-500">
                Manage hospital medical and non-medical assets.
            </p>
        </div>

        <button
            class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700">
            + Add Asset
        </button>

    </div>

    <!-- Search -->

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex gap-4">

            <input
                type="text"
                placeholder="Search asset..."
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

                    <th class="px-6 py-4 text-left">Code</th>

                    <th class="px-6 py-4 text-left">Asset Name</th>

                    <th class="px-6 py-4 text-left">Room</th>

                    <th class="px-6 py-4 text-left">Category</th>

                    <th class="px-6 py-4 text-left">Status</th>

                    <th class="px-6 py-4 text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">

                    <td class="px-6 py-4">AST-001</td>

                    <td class="px-6 py-4 font-medium">
                        Ventilator ICU
                    </td>

                    <td class="px-6 py-4">
                        ICU
                    </td>

                    <td class="px-6 py-4">
                        Medical
                    </td>

                    <td class="px-6 py-4">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">
                            Active
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">

                        <button class="text-blue-600">
                            Detail
                        </button>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="px-6 py-4">AST-002</td>

                    <td class="px-6 py-4 font-medium">
                        Patient Monitor
                    </td>

                    <td class="px-6 py-4">
                        NICU
                    </td>

                    <td class="px-6 py-4">
                        Medical
                    </td>

                    <td class="px-6 py-4">
                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">
                            Maintenance
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">

                        <button class="text-blue-600">
                            Detail
                        </button>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="px-6 py-4">AST-003</td>

                    <td class="px-6 py-4 font-medium">
                        Generator
                    </td>

                    <td class="px-6 py-4">
                        Utility
                    </td>

                    <td class="px-6 py-4">
                        Non Medical
                    </td>

                    <td class="px-6 py-4">
                        <span class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">
                            Broken
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">

                        <button class="text-blue-600">
                            Detail
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>