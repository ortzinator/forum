@forelse ($threads as $thread)
    <article class="py-5">
        <div class="flex flex-row space-x-4 text-xl">
            <h4 class="flex-grow">
                <a class="underline text-blue-800" href="{{ $thread->path() }}">
                    @if (Auth::check() && $thread->hasUpdatesFor(Auth::user()))
                        <strong>{{ $thread->title }}</strong>
                    @else
                        {{ $thread->title }}
                    @endif
                </a>
            </h4>
            <span>{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</span>
        </div>
        <div class="text-xl">Created by: <a class="text-blue-800" href="{{ route('profile', $thread->user) }}">{{ $thread->user->name }}</a></div class="text-xl">
        <div class="prose lg:prose-lg my-2 max-w-none">{{ $thread->body }}</div>
    </article>
@empty
    <p>No threads found</p>
@endforelse