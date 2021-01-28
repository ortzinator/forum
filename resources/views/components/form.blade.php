@props([
    'method' => 'POST',
])

<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    {{ $attributes }}>
    @if ($method != 'GET')
        @csrf
    @endif
    
    @if (! in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    {{ $slot }}
</form>