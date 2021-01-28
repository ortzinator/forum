<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-white rounded-lg px-5 py-2']) }}>
    {{ $slot }}
</button>
