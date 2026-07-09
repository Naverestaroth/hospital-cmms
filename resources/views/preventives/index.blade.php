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
                    Preventive Maintenance
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage Scheduled Preventive Maintenance.
                </p>
            </div>

            <a
                href="{{ route('preventives.create') }}"
                class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700">

                + Schedule Maintenance

            </a>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex gap-4">

                <input
                    type="text"
                    placeholder="Search maintenance..."
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

                        <th class="px-6 py-4 text-left">Asset</th>

                        <th class="px-6 py-4 text-left">Schedule Date</th>

                        <th class="px-6 py-4 text-left">Technician</th>

                        <th class="px-6 py-4 text-left">Status</th>

                        <th class="px-6 py-4 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>
                    @forelse ($preventives as $preventive)
                    <tr class="border-t">
                        <td class="px-6 py-4">
                            {{ $preventive->asset->asset_name }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $preventive->schedule_date }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->technician }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $preventive->status }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($preventive->status === 'Scheduled')

                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">
                                Scheduled
                            </span>

                            @elseif ($preventive->status === 'Completed')

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">
                                Completed
                            </span>

                            @else

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                                {{ $preventive->status }}
                            </span>

                            @endif
                        </td>

                        <td class="space-x-3 px-6 py-4 text-center">

                            <button class="text-blue-600 hover:underline">

                                Detail

                            </button>



                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-500">
                            No preventive maintenance schedules available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>