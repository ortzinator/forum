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
        @include('threads._list')
    </div>
    {{ $threads->withQueryString()->links() }}
</x-app-layout>