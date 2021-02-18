{{-- Editing --}}
<div id="threadop" class="bg-gray-200 p-5 border border-gray-300 rounded-lg v-cloak--hidden" v-if="editing">
    <div class="flex items-center mb-10">
        <input type="text" name="title" class="flex-grow mr-5" v-model="form.title">
        @can('update', $thread)
        @endcan
    </div>
    <div class="mb-5 bg-white p-5">
        {{-- <textarea name="body" id="" rows="10" class="w-full" v-model="form.body"></textarea> --}}
        <wysiwyg v-model="form.body"></wysiwyg>
    </div>
    <div>
        <button class="bg-blue-400 px-2 py-1 text-white text-xs" @click="update">Update</button>
        <button class="bg-gray-400 px-2 py-1 text-white text-xs" @click="cancel">Cancel</button>
        <button class="bg-red-500 px-2 py-1 text-white text-xs">Delete Thread</button>
    </div>
</div>

{{-- Viewing --}}
<div id="threadop" class="bg-gray-200 p-5 border border-gray-300 rounded-lg" v-else>
    <h2 class="font-medium mb-5 text-xl" v-text="form.title"></h2>
    <div class="flex justify-between mb-8">
        <div class="flex items-center space-x-4">
            <x-avatar class="border border-gray-300 shadow-md" :user="$thread->user"/>
            <a href="{{ route('profile', $thread->user->name) }}" class="text-2xl">{{ $thread->user->name }}</a>
        </div>
    </div>
    <div class="mb-5 prose" v-html="form.body"></div>
    <div v-if="authorize('owns', thread)">
        <button @click="editing = true">Edit</button>
    </div>
</div>