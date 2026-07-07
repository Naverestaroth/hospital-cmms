<div {{ $attributes->merge([
    'class' => 'bg-white rounded-3xl border border-slate-200 shadow-sm transition-all duration-300'
]) }}>
    {{ $slot }}
</div>