<x-app-layout>

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>

                <a href="{{ route('documents.index') }}"
                    class="text-sm text-emerald-600 hover:underline">

                    ← Back to Documents

                </a>

                <h1 class="mt-3 text-3xl font-bold text-slate-900">

                    {{ $document->title }}

                </h1>

                <p class="mt-2 text-slate-500">

                    Document Code: {{ $document->document_code }}

                </p>

            </div>

        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-xl font-semibold">

                    Document Information

                </h2>

                <div class="mt-6 grid grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-slate-500">Document Code</p>

                        <p class="mt-1 font-semibold">{{ $document->document_code }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Document Name</p>

                        <p class="mt-1 font-semibold">{{ $document->title }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Document Type</p>

                        <p class="mt-1 font-semibold">{{ $document->document_type }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Asset Relation</p>

                        <p class="mt-1 font-semibold">{{ $document->asset ? $document->asset->asset_name : '-' }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Revision</p>

                        <p class="mt-1 font-semibold">{{ $document->revision ?? '-' }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Issue Date</p>

                        <p class="mt-1 font-semibold">{{ $document->issue_date ? \Carbon\Carbon::parse($document->issue_date)->format('d M Y') : '-' }}</p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">Expiry Date</p>

                        <p class="mt-1 font-semibold">{{ $document->expiry_date ? \Carbon\Carbon::parse($document->expiry_date)->format('d M Y') : '-' }}</p>

                    </div>

                </div>

            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between">

                <div>

                    <h2 class="text-xl font-semibold">

                        File Actions

                    </h2>

                    <p class="mt-2 text-sm text-slate-500">

                        Click the button below to view the attached PDF document.

                    </p>

                </div>

                <div class="mt-6">

                    @if ($document->file_path)

                    <a

                        href="{{ route('documents.view', $document) }}"

                        target="_blank"

                        class="w-full inline-block text-center rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">

                        View PDF File

                    </a>

                    @else

                    <p class="text-slate-500 text-sm">No file uploaded for this document.</p>

                    @endif

                </div>

            </div>

        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-xl font-semibold">

                Description / Notes

            </h2>

            <p class="mt-4 text-slate-700 whitespace-pre-wrap">

                {{ $document->description ?? 'No description provided.' }}

            </p>

        </div>

    </div>

</x-app-layout>
