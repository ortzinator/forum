<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Threads') }}
            @isset($channelName)
                in <em>{{ $channelName }}</em>
            @endisset
        </h2>
    </x-slot>
    
    <div id="threads" class="flex">
        <div class="mr-20">
            <div class="divide-y">
                @include('threads._list')
            </div>
            {{ $threads->withQueryString()->links() }}
        </div>
        <div>
            <div class="border p-5 w-60">
                <div>
                    <form action="/threads/search">
                        <input type="text" name="q" placeholder="Search..." class="mb-5">
                    </form>
                </div>
                @if (count($trending))
                    <h3 class="text-xl mb-3">Trending Threads</h3>
                    <ul class="text-sm list-none divide-y-2 divide-gray-100">
                        @foreach ($trending as $thread)
                            <li>
                                <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>