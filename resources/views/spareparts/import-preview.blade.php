<x-app-layout>
    <div class="max-w-6xl">
        <h1 class="mb-8 text-3xl font-bold text-slate-900">
            Preview Excel Import (Spare Parts)
        </h1>

        @if(session('error'))
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        @php
        $payload = session('spareparts_import_preview');
        $rows = $payload['previewRows'] ?? [];
        $summary = $payload['summary'] ?? [];
        $errors = $payload['errors'] ?? [];

        $debugCounts = session('spareparts_import_preview_debug');
        @endphp

        <div class="mt-4">
            @if(!empty($debugCounts))
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-2 text-sm font-semibold text-slate-900">
                    Import preview summary
                </div>

                <div class="space-y-2 text-sm text-slate-700 flex gap-6">
                    <div>
                        <span class="font-medium text-slate-900">Total Rows:</span>
                        {{ $debugCounts['previewRows_count'] ?? '-' }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">Skipped:</span>
                        {{ $debugCounts['skipped'] ?? 0 }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">Duplicates:</span>
                        {{ $debugCounts['duplicates'] ?? 0 }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">Failed:</span>
                        {{ $debugCounts['failed'] ?? 0 }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm mt-4">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr class="border-t transition hover:bg-slate-50">
                        <th class="px-6 py-4 text-left">Part Code</th>
                        <th class="px-6 py-4 text-left">Part Name</th>
                        <th class="px-6 py-4 text-left">Stock</th>
                        <th class="px-6 py-4 text-left">Unit</th>
                        <th class="px-6 py-4 text-left">Location</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $row)
                    <tr class="border-t transition hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $row['part_code'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $row['part_name'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $row['stock'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $row['unit'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $row['location'] ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                            No rows to preview.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <a
                href="{{ route('spareparts.import.upload') }}"
                class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-100">
                ← Back
            </a>

            <form action="{{ route('spareparts.import.confirm') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Import {{ count($rows) }} Spare Parts
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
