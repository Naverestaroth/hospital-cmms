<x-app-layout>

    <div class="max-w-5xl space-y-8">

        <!-- Header -->
        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Upload Document
            </h1>

            <p class="mt-2 text-slate-500">
                Upload SOP, calibration certificates, and technical manuals for hospital assets.
            </p>

        </div>

        @if ($errors->any())

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

            <ul class="space-y-2 text-sm text-red-600">

                @foreach ($errors->all() as $error)

                <li>• {{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        @if(session('success'))

        <div class="mb-6 rounded-xl bg-emerald-100 p-4 text-emerald-700">

            {{ session('success') }}

        </div>

        @endif

        <form
            action="{{ route('documents.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8">

            @csrf

            <!-- Document Information -->

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <h2 class="text-xl font-semibold text-slate-900">

                    Document Information

                </h2>

                <div class="mt-8 grid gap-6 md:grid-cols-2">

                    <div>

                        <label class="mb-2 block font-medium">

                            Document Code

                        </label>

                        <input
                            type="text"
                            name="document_code"
                            value="{{ old('document_code') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                    </div>

                    <div>

                        <label class="mb-2 block font-medium">

                            Title

                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                    </div>

                    <div>

                        <label class="mb-2 block font-medium">

                            Document Type

                        </label>

                        <select
                            name="document_type"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                            <option value="">Select Type</option>

                            <option value="SOP">SOP</option>

                            <option value="Calibration Certificate">
                                Calibration Certificate
                            </option>

                            <option value="User Manual">
                                User Manual
                            </option>

                            <option value="Service Manual">
                                Service Manual
                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="mb-2 block font-medium">

                            Related Asset

                        </label>

                        <select
                            name="asset_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                            <option value="">

                                Select Asset

                            </option>

                            @foreach($assets as $asset)

                            <option
                                value="{{ $asset->id }}">

                                {{ $asset->asset_name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="mb-2 block font-medium">

                            Revision

                        </label>

                        <input
                            type="text"
                            name="revision"
                            placeholder="Rev 1.0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                    </div>

                    <div>

                        <label class="mb-2 block font-medium">

                            Issue Date

                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                    </div>

                    <div>

                        <label class="mb-2 block font-medium">

                            Expiry Date

                        </label>

                        <input
                            type="date"
                            name="expiry_date"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">

                    </div>

                </div>

                <div class="mt-6">

                    <label class="mb-2 block font-medium">

                        Description

                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none">{{ old('description') }}</textarea>

                </div>

            </div>

            <!-- Attachment -->

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                <h2 class="text-xl font-semibold text-slate-900">

                    Attachment

                </h2>

                <p class="mt-2 text-sm text-slate-500">

                    Upload PDF document.

                </p>

                <div class="mt-6 rounded-2xl border-2 border-dashed border-slate-300 p-10 text-center">

                    <div class="text-5xl">

                        📄

                    </div>

                    <p class="mt-4 font-medium">

                        Choose PDF File

                    </p>

                    <p class="mt-1 text-sm text-slate-500">

                        PDF (Max 10 MB)

                    </p>

                    <input
                        type="file"
                        name="file"
                        accept=".pdf"
                        class="mt-6 block w-full text-sm">

                </div>

            </div>

            <!-- Button -->

            <div class="flex justify-end gap-4">

                <a
                    href="{{ route('documents.index') }}"
                    class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white transition hover:bg-emerald-700">

                    Upload Document

                </button>

            </div>

        </form>

    </div>

</x-app-layout>