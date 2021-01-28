<div class="p-5 bg-gray-200 border-gray-600 mb-5">
    <div class="">
        {{ $profileUser->name }} created a thread <a href="{{$activity->subject->path()}}">{{ $activity->subject->title }}</a>
    </div>
    <div class="my-5">
        {{ Str::of($activity->subject->body)->limit(100, ' [...]') }}
    </div>
</div>