@props(['header' => null, 'theadClass' => null, 'tbodyClass' => null, 'rowClass' => null])

<div {{ $attributes->merge(['class' => 'relative rounded-[28px] border border-white/[0.35] bg-white/[0.08] shadow-[0_12px_28px_rgba(15,23,42,0.06)] backdrop-blur-[0.03] overflow-hidden']) }}>
    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-50 pointer-events-none"></div>

    @if($header)
        <div class="relative z-10 w-full flex items-center justify-between px-6 py-5 bg-white/[0.02] hover:bg-white/[0.06] transition text-left border-b border-white/[0.15]">
            {{ $header }}
        </div>
    @endif

    <div class="relative z-10 overflow-x-auto">
        <table class="min-w-full">
            <thead class="{{ $theadClass ?? 'bg-white/[0.04] text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-white/[0.15]' }}">
                {{ $thead ?? '' }}
            </thead>
            <tbody class="{{ $tbodyClass ?? 'bg-white divide-y divide-slate-100' }}">
                {{ $slot ?? '' }}
            </tbody>
        </table>
    </div>
</div>
