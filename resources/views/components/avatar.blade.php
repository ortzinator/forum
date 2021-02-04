@props([
    'width' => 50,
    'height' => 50,
    'user'
])

@if($user->avatar_path)
    <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="Avatar for user {{ $user->name }}" width="{{$width}}" height="{{$height}}">
@else
    <img src="{{ asset('images/tortilla.jpg') }}" alt="Default avatar" width="{{$width}}" height="{{$height}}">
@endif