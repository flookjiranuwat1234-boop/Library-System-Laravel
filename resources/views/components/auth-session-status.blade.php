@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert-success']) }}>
        {{ $status }}
    </div>
@endif
