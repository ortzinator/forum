<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Threads') }}
            @isset($channelName)
                in <em>{{ $channelName }}</em>
            @endisset
        </h2>
    </x-slot>
    
    <div id="threads" class="divide-y">
        @forelse ($threads as $thread)
            <article class="py-5">
                <div class="flex flex-row">
                    <h4 class="flex-grow">
                        <a class="underline" href="{{ $thread->path() }}">
                            @if (Auth::check() && $thread->hasUpdatesFor(Auth::user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <strong>{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</strong>
                </div>
                <div class="prose lg:prose-lg my-2 max-w-none">{{ $thread->body }}</div>
            </article>
        @empty
            <p>No threads found</p>
        @endforelse
    </div>
</x-app-layout>