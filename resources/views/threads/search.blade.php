<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Search
        </h2>
    </x-slot>

    <search inline-template>
        <ais-instant-search :search-client="searchClient" index-name="threads" :initial-ui-state="{ threads: { query: '{{ request('q') }}' } }">
            <div id="threads" class="flex">
                <div class="mr-20 w-full">
                    <div class="divide-y">
                        <ais-hits>
                            <div slot="item" slot-scope="{ item }">
                                <h2>
                                    <a :href="item.path" v-text="item.title"></a>
                                </h2>
                            </div>
                        </ais-hits>
                    </div>
                </div>
                <div>
                    <div class="border p-5 space-y-5 w-60">
                        <div>
                            <ais-search-box placeholder="Search..." />
                        </div>
                        <div>
                            Filter by Channel
                            <ais-refinement-list attribute="channel.name" />
                        </div>
                        <div>
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
            </div>
        </ais-instant-search>
    </search>
        
</x-app-layout>