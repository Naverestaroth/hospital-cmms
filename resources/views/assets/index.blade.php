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
                <a
                    href="{{ route('assets.create') }}"
                    class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700">
                    + Add Asset
                </a>
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
                    @forelse ($assets as $asset)
                    <tr class="border-t">
                        <td class="px-6 py-4">
                            {{ $asset->asset_code }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $asset->asset_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $asset->room }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $asset->category }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($asset->status === 'Active')
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">
                                Active
                                </sßpan>
                                @elseif ($asset->status === 'Maintenance')
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-sm text-yellow-700">
                                    Maintenance
                                </span>
                                @elseif ($asset->status === 'Broken')
                                <span class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700">
                                    Broken
                                </span>
                                @else
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                                    {{ $asset->status }}
                                </span>
                                @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:underline">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-500">
                            No asset data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>