<x-app-layout>
    <div class="max-w-6xl pb-28">
        <h1 class="mb-8 text-3xl font-bold text-slate-900">
            Preview Excel Import
        </h1>

        @if(session('error'))
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        @php
        $payload = session('assets_import_preview');
        $rows = $payload['previewRows'] ?? [];
        $summary = $payload['summary'] ?? [];
        $errors = $payload['errors'] ?? [];

        $debug = $payload['debug_excel_parse'] ?? null;

        $debug_sheet_names = $debug['sheet_names'] ?? [];
        $debug_heading_row_1_values = $debug['heading_row_1_values'] ?? [];
        $debug_parsed_column_keys_heading_row_1 = $debug['parsed_column_keys_heading_row_1'] ?? [];
        $debug_first_data_row_2_values = $debug['first_data_row_2_values'] ?? [];

        $debugCounts = session('assets_import_preview_debug');
        @endphp
        <div class="mt-4">
            @if(!empty($debugCounts))
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-2 text-sm font-semibold text-slate-900">
                    Import preview debug (controller/session)
                </div>

                <div class="space-y-2 text-sm text-slate-700">
                    <div>
                        <span class="font-medium text-slate-900">previewRows_count:</span>
                        {{ $debugCounts['previewRows_count'] ?? '-' }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">imported:</span>
                        {{ $debugCounts['imported'] ?? 0 }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">skipped:</span>
                        {{ $debugCounts['skipped'] ?? 0 }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">updated:</span>
                        {{ $debugCounts['updated'] ?? 0 }}
                    </div>
                    <div>
                        <span class="font-medium text-slate-900">failed:</span>
                        {{ $debugCounts['failed'] ?? 0 }}
                    </div>

                    @if(!empty($debugCounts['errors_sample']))
                    <div class="mt-2">
                        <div class="font-medium text-slate-900">errors_sample:</div>
                        <div class="break-words text-xs text-slate-600">
                            {{ json_encode($debugCounts['errors_sample']) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <x-table class="mt-4">
    <x-slot name="thead">
        <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Asset Code</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Asset Name</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Brand</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Type</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Serial Number</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Procurement Year</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Room</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Description</th>
        </tr>
    </x-slot>

    @forelse($rows as $row)
        <tr class="transition hover:bg-slate-50/80">
            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $row['asset_code'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $row['asset_name'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['brand'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['type'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ $row['serial_number'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['procurement_year'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $row['room'] ?? '—' }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">
                @if(!empty($row['status']))
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                        {{ $row['status'] }}
                    </span>
                @else
                    —
                @endif
            </td>
            <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $row['description'] ?? '—' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="px-6 py-12 text-center text-sm text-slate-500">No rows to preview.</td>
        </tr>
    @endforelse
</x-table>

        @php
            $totalRows = count($rows);
            $skippedCount = $summary['skipped'] ?? $debugCounts['skipped'] ?? 0;
            $updatedCount = $summary['updated'] ?? $debugCounts['updated'] ?? 0;
            $createdCount = max(0, $totalRows - $updatedCount);
        @endphp

        <!-- Floating Glass Action Bar -->
        <style>
            /* Neutralize parent containing block trap caused by .page-enter transform/will-change */
            main.page-enter {
                will-change: auto !important;
                transform: none !important;
                filter: none !important;
            }

            .floating-action-bar {
                position: fixed !important;
                bottom: 24px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                z-index: 9999 !important;
                max-width: 900px !important;
                width: calc(100% - 32px) !important;
            }

            @media (min-width: 768px) {
                .floating-action-bar {
                    left: calc(50% + 9.75rem) !important;
                }
            }

            @media (min-width: 1024px) {
                .floating-action-bar {
                    left: calc(50% + 10.5rem) !important;
                }
            }

            @keyframes glassSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(1.25rem);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-glass-bar {
                animation: glassSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        </style>

        <div class="floating-action-bar animate-glass-bar">
            <div class="rounded-[24px] border border-slate-200/80 bg-white/85 p-3.5 md:px-6 md:py-3.5 shadow-xl shadow-slate-900/10 backdrop-blur-xl transition-all duration-300">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
                    
                    <!-- Left: Summary Badges -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mr-1 hidden sm:inline">Summary:</span>
                        
                        <!-- Total Rows -->
                        <div class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-100/80 px-3 py-1.5 font-medium text-slate-700">
                            <span class="text-slate-400">Total:</span>
                            <span class="font-semibold text-slate-900">{{ $totalRows }}</span>
                        </div>

                        <!-- Create -->
                        <div class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200/70 bg-emerald-50/80 px-3 py-1.5 font-medium text-emerald-800">
                            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span class="text-emerald-600/80">Create:</span>
                            <span class="font-semibold text-emerald-900">{{ $createdCount }}</span>
                        </div>

                        <!-- Update -->
                        <div class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200/70 bg-blue-50/80 px-3 py-1.5 font-medium text-blue-800">
                            <span class="inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                            <span class="text-blue-600/80">Update:</span>
                            <span class="font-semibold text-blue-900">{{ $updatedCount }}</span>
                        </div>

                        <!-- Skip -->
                        <div class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200/70 bg-amber-50/80 px-3 py-1.5 font-medium text-amber-800">
                            <span class="inline-block h-2 w-2 rounded-full bg-amber-500"></span>
                            <span class="text-amber-600/80">Skip:</span>
                            <span class="font-semibold text-amber-900">{{ $skippedCount }}</span>
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex items-center justify-end gap-2.5">
                        <a
                            href="{{ route('assets.import.upload') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300/90 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-1">
                            Cancel
                        </a>

                        <form action="{{ route('assets.import.confirm') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-md shadow-blue-600/25 transition duration-200 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 active:scale-[0.98]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                Import Data
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>