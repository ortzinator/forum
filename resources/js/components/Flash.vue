<template>
    <div
        class="bg-blue-200 border border-blue-300 font-medium max-w-6xl mx-auto my-4 p-4 rounded-lg fixed bottom-5 right-5"
        x-data="{ open: true }"
        x-show="open"
        role="alert"
        v-show="show"
    >
        <div class="flex">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="stroke-current text-black h-6 w-6 inline mx-2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <div class="mx-2 font-bold">Success!</div>
            <div>{{ body }}</div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message'],
    data() {
        return {
            body: this.message,
            show: false
        };
    },
    created() {
        if (this.message) {
            this.flash(this.message);
        }

        window.events.$on('flash', message => {
            this.flash(message);
        });
    },

    methods: {
        flash(message) {
            this.body = message;
            this.show = true;
            this.hide();
        },
        hide() {
            setTimeout(() => {
                this.show = false;
            }, 3000);
        }
    }
};
</script>
