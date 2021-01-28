<div class="p-5 bg-gray-200 border-gray-600 mb-5">
    {{ $profileUser->name }} <span class="text-gray-500">replied to thread</span>
    <a href="{{$activity->subject->thread->path()}}">{{ $activity->subject->thread->title }}</a>
</div>