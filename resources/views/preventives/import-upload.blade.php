<x-app-layout>
    <div class="max-w-4xl">
        <h1 class="mb-8 text-3xl font-bold text-slate-900">
            Import Preventive Maintenance from Excel
        </h1>

        @if(session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <form
                action="{{ route('preventives.import.preview-action') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6">

                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Upload .xlsx / .xls file
                    </label>

                    <input
                        type="file"
                        name="excel_file"
                        accept=".xlsx,.xls"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
                    @error('excel_file')
                        <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                    @enderror

                    <p class="mt-2 text-sm text-slate-500">
                        Expected columns: Ruang, Tanggal, Nama Alat, Merk, Type, Serial Number, Checklist, Pengecekkan Baik, Pengecekkan Rusak, Kondisi Alat, Keterangan, Engineer.
                    </p>
                </div>

                <div class="flex justify-end gap-4">
                    <a
                        href="{{ route('preventives.index') }}"
                        class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                        Preview Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
