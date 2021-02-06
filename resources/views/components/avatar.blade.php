@props([
    'width' => 50,
    'height' => 50,
    'user'
])

<img src="{{ $user->avatar_path }}" alt="Avatar for user {{ $user->name }}" width="{{$width}}" height="{{$height}}" {{ $attributes }}>