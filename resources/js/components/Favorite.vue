<template>
    <div class="flex">
        <span class="mr-2 text-gray-400" v-text="count"></span>
        <button type="submit" @click="toggle">
            <svg
                :class="classes"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                />
            </svg>
        </button>
    </div>
</template>

<script>
export default {
    props: ['reply'],
    data() {
        return {
            count: this.reply.favoritesCount,
            active: this.reply.isFavorited
        };
    },

    computed: {
        classes() {
            return [
                'h-6',
                'w-6',
                'text-gray-300',
                'hover:text-red-400',
                this.active ? 'fill-current' : 'stroke-current'
            ];
        }
    },

    methods: {
        toggle() {
            if (this.active) {
                axios.delete('/replies/' + this.reply.id + '/favorites');
                this.active = false;
                this.count--;
            } else {
                axios.post('/replies/' + this.reply.id + '/favorites');
                this.active = true;
                this.count++;
            }
        }
    }
};
</script>
