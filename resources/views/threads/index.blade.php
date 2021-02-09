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
        <div class="">
            <div class="border p-5 w-60">
                <h3 class="text-xl">Trending Threads</h3>

                @foreach ($trending as $thread)
                    <li>
                        <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>