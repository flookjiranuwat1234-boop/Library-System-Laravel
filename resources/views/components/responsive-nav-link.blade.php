@props(['active'])

@php
$classes = ($active ?? false)
            ? 'mx-2 block rounded-xl bg-emerald-500/15 px-3 py-2.5 text-start text-base font-semibold text-emerald-300 transition'
            : 'mx-2 block rounded-xl px-3 py-2.5 text-start text-base font-medium text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-emerald-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
