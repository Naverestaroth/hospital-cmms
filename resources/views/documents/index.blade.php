<x-app-layout>

    <div class="space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-100 p-4 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-red-100 p-4 text-red-700">
            {{ session('error') }}
        </div>
        @endif

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
                class="ds-button-primary">

                + New Document

            </a>

        </div>

        <!-- Search -->

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <form action="{{ route('documents.index') }}" method="GET" class="flex gap-4">

                <input
                    type="text"
                    name="search"
                    placeholder="Search Document..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('documents.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>

            </form>

        </div>

        <!-- Table -->

        <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    @php
                    function sortUrl($field)
                    {
                        return request()->fullUrlWithQuery([
                            'sort' => $field,
                            'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc'
                        ]);
                    }
                    @endphp

                    <tr class="border-t transition hover:bg-slate-50">

                        <th class="px-6 py-4 text-left">No</th>
                        
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('document_code') }}">
                                Doc Code
                            </a>
                        </th>
                        
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('title') }}">
                                Doc Name
                            </a>
                        </th>
                        
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('document_type') }}">
                                Doc Type
                            </a>
                        </th>
                        
                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('issue_date') }}">
                                Date Uploaded
                            </a>
                        </th>
                        
                        <th class="px-6 py-4 text-center">View File</th>
                        
                        <th class="px-6 py-4 text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($documents as $document)

                    <tr class="border-t border-slate-100">

                        <td class="px-6 py-4">
                            {{ $documents->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $document->document_code }}
                        </td>

                        <td class="px-6 py-4 font-medium">
                            {{ $document->title }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $document->document_type }}
                        </td>

                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($document->issue_date)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 text-center">

                            @if ($document->file_path)

                            <a
                                href="{{ route('documents.view', $document) }}"
                                target="_blank"
                                class="text-blue-600 hover:underline">

                                View

                            </a>

                            @endif

                        </td>


                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a
                                    href="{{ route('documents.show', $document) }}"
                                    class="text-blue-600 hover:underline">
                                    View
                                </a>

                                <a
                                    href="{{ route('documents.edit', $document) }}"
                                    class="text-emerald-700 hover:underline">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('documents.destroy', $document) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this document?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="py-10 text-center text-slate-500">

                            No Document data available.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $documents->links() }}
        </div>

    </div>

</x-app-layout>