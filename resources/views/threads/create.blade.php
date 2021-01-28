<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Thread
        </h2>
    </x-slot>
    
    <div class="lg:w-3/5 md:w-4/5 md:mx-auto w-full">
        <form action="/threads" class="space-y-5" method="POST">
            @csrf
            <div class="flex flex-col">
                <label for="channel">Channel</label>
                <select name="channel_id" id="channel_id">
                    <option value="">Choose one...</option>
                    @foreach ($channels as $channel)
                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}">
            </div>

            <div class="flex flex-col">
                <label for="body"></label>
                <textarea name="body" id="body" rows="5" placeholder="What would you like to say?">{{ old('body') }}</textarea>
            </div>

            <button type="submit" class="bg-gray-500 hover:bg-blue-500 text-white rounded-lg px-5 py-2">Submit</button>
        </form>
        @if(count($errors))
            <ul class="bg-red-100 text-red-700 p-5 rounded-lg border-red-200 my-6">
                @foreach ($errors->all() as $er)
                    <li>{{ $er }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>