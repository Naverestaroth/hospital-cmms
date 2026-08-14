<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Label - {{ $asset->asset_code ?: 'Asset' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .print-card {
                border: 2px solid #000 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-6 text-slate-900 font-sans">

    <div class="no-print mb-6 flex items-center gap-3">
        <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow transition flex items-center gap-2 text-sm">
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print Label
        </button>
        <button onclick="window.close()" class="bg-white hover:bg-slate-200 border border-slate-300 text-slate-700 px-4 py-2.5 rounded-xl font-semibold shadow-sm transition text-sm">
            Close
        </button>
    </div>

    <!-- Physical Equipment Tag Label Container -->
    <div class="print-card bg-white border-2 border-slate-900 rounded-2xl p-6 w-[360px] shadow-lg flex flex-col items-center text-center space-y-3">
        
        <!-- Header / Hospital CMMS Tag -->
        <div class="w-full border-b border-slate-300 pb-2 flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Hospital CMMS</span>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 border border-slate-300 text-slate-700">EQUIPMENT TAG</span>
        </div>

        <!-- QR Code Display -->
        <div class="p-2 bg-white border border-slate-200 rounded-xl inline-block">
            {!! $qrSvg !!}
        </div>

        <!-- Asset Code -->
        <div>
            <span class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Asset Code</span>
            <div class="font-mono font-extrabold text-xl tracking-tight text-slate-900">
                {{ $asset->asset_code ?: 'AST-' . $asset->id }}
            </div>
        </div>

        <!-- Asset Info Details -->
        <div class="w-full border-t border-slate-200 pt-3 text-left space-y-1.5 text-xs">
            <div class="flex justify-between items-start">
                <span class="text-slate-500 font-medium">Equipment Name:</span>
                <span class="font-bold text-slate-900 text-right max-w-[200px] leading-tight">{{ $asset->asset_name }}</span>
            </div>

            @if($asset->brand)
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">Brand:</span>
                <span class="font-semibold text-slate-800 text-right">{{ $asset->brand }}</span>
            </div>
            @endif

            @if($asset->type)
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">Type / Model:</span>
                <span class="font-semibold text-slate-800 text-right">{{ $asset->type }}</span>
            </div>
            @endif

            @if($asset->serial_number)
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">Serial No:</span>
                <span class="font-mono font-semibold text-slate-800 text-right">{{ $asset->serial_number }}</span>
            </div>
            @endif

            @if($asset->room)
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">Room / Location:</span>
                <span class="font-semibold text-slate-800 text-right">{{ $asset->room }}</span>
            </div>
            @endif
        </div>

    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
