<template>
    <div
        class="alert-container"
        x-data="{ open: true }"
        x-show="open"
        role="alert"
        v-show="show"
    >
        <div class="alert" :class="`alert-${level}`">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="stroke-current h-6 w-6 inline mr-2"
                v-if="level == 'success'"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="stroke-current h-6 w-6 inline mr-2"
                v-if="level == 'error'"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <div v-text="body"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message'],
    data() {
        return {
            body: this.message,
            level: 'success',
            show: false
        };
    },
    created() {
        if (this.message) {
            this.flash(this.message);
        }

        window.events.$on('flash', data => {
            this.flash(data);
        });
    },

    methods: {
        flash(data) {
            if (data) {
                this.body = data.message;
                this.level = data.level;
            }

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
