<div class="p-5 bg-gray-200 border-gray-600 mb-5">
    {{ $profileUser->name }} <span class="text-gray-500">favorited a reply</span>
    <a href="{{$activity->subject->favorited->path()}}">{{ $activity->subject->favorited->body }}</a>
</div>