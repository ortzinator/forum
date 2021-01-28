<div class="bg-blue-200 border border-blue-300 font-medium max-w-6xl mx-auto my-4 p-4 rounded-lg flex justify-between"
    x-data="{ open: true }"
    x-show="open">
    <div>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="stroke-current text-black h-6 w-6 inline mx-2">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>
        {{ $slot }}
    </div>
    <button type="button" @click="open = false">
        <svg xmlns="http://www.w3.org/2000/svg" 
            fill="none" viewBox="0 0 24 24" stroke="currentColor"
            class="stroke-current text-black h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </button>
</div>

{{-- <div x-data="{ open: false }">
    <button @click="open = true">Open Dropdown</button>

    <ul
        x-show="open"
        @click.away="open = false"
    >
        Dropdown Body
    </ul>
</div> --}}