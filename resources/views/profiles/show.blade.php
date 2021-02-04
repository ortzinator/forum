<x-app-layout>
    <x-slot name="header">
        User Profile
    </x-slot>
    
    <div>
        <h1 class="font-semibold text-4xl text-gray-800 leading-loose">
            {{ $profileUser->name }}
            <small>signed up {{ $profileUser->created_at->diffForHumans() }}</small>
        </h1>

        @can('update', $profileUser)
            <form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" id="" class="block">
                <button type="submit" class="text-white rounded-lg px-5 py-2 bg-blue-800">Add Avatar</button>
            </form>
        @endcan

        <div><x-avatar :user="$profileUser"/></div>

        <h2 class="text-2xl leading-loose mb-7 border-b">Activity Feed</h2>

        <div id="activity">
            @forelse ($activities as $date => $activity)
                <div class="mb-5 text-2xl">{{ $date }}</div>
                @foreach ($activity->filter() as $record)
                    <x-dynamic-component :component="$record->type" :profile-user="$profileUser" :activity="$record" />
                @endforeach
            @empty
                <p>This user has not done anything</p>
            @endforelse
        </div>
    </div>
</x-app-layout>