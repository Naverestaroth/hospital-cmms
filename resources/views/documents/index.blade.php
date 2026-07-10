<x-app-layout>

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Document Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage hospital Documents information.
                </p>
            </div>

            <a
                href="{{ route('documents.create') }}"
                class="rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">

                + Add Document

            </a>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex gap-4">

                <input
                    type="text"
                    placeholder="Search Document..."
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

                        <th class="px-6 py-4 text-left">Doc Code</th>
                        <th class="px-6 py-4 text-left">Doc Name</th>
                        <th class="px-6 py-4 text-left">Doc Type</th>
                        <th class="px-6 py-4 text-left">Date Uploaded</th>
                        <th class="px-6 py-4 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($documents as $document)

                    <tr class="border-t border-slate-100">

                        <td class="px-6 py-4">
                            {{ $document->document_code }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $document->document_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $document->document_type }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $document->date_uploaded }}
                        </td>


                        <td class="px-6 py-4 text-center">

                            <a
                                href="{{ route('document.edit',$document) }}"
                                class="text-emerald-600 hover:underline">

                                Edit

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" class="py-10 text-center text-slate-500">

                            No Document data available.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>