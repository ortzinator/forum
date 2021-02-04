<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $thread->title }}
        </h2>
    </x-slot>
    
    <thread-view :init-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="flex flex-row">
            <div id="thread" class="flex flex-grow flex-col px-5">
                <div id="threadop" class="bg-gray-200 p-5 border border-gray-300 rounded-lg">
                    <div class="flex justify-between mb-8">
                        <div class="">
                            <a href="{{ route('profile', $thread->user->name) }}">{{ $thread->user->name }}</a>
                            <x-avatar :user="$thread->user"/>
                        </div>
                        @can('update', $thread)    
                            <div class="">
                                <form action="{{ $thread->path() }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete Thread</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                    <div>{{ $thread->body }}</div>
                </div>

                <replies :data="{{$thread->replies}}" @added="repliesCount++" @removed="repliesCount--"></replies>

                {{-- <div id="pagination">{{ $replies->links() }}</div> --}}
            </div>
            <div id="thread-meta" class="flex-none w-64 h-64 border border-gray-200 p-5 rounded-lg">
                <p class="mb-2">Thread was published {{ $thread->created_at->diffForHumans() }}
                    and has <span v-text="repliesCount"></span> {{ Str::plural('reply', $thread->replies_count) }}</p>
                <subscribe-button :active="{{ json_encode($thread->isSubscribed) }}"></subscribe-button>
            </div>
        </div>
    </thread-view>
</x-app-layout>