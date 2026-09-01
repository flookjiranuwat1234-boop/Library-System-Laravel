<button {{ $attributes->merge(['type' => 'button', 'class' => 'button-secondary']) }}>
    {{ $slot }}
</button>
