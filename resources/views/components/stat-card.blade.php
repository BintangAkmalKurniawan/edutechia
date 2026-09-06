@props(['label', 'value', 'hint' => null])
<div class="card p-5">
    <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
    <p class="mt-2 font-display text-3xl font-extrabold text-white">{{ $value }}</p>
    @if ($hint)<p class="mt-2 text-xs text-slate-500">{{ $hint }}</p>@endif
</div>
