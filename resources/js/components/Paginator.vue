<template>
    <nav
        role="navigation"
        aria-label="Pagination Navigation"
        class="flex items-center"
        v-if="shouldPaginate"
    >
        <a
            href="#"
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
            v-show="prevUrl"
            @click.prevent="page--"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="stroke-current h-4 w-4 inline mr-2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
            Previous
        </a>
        <a
            href="#"
            class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
            v-show="nextUrl"
            @click.prevent="page++"
        >
            Next
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="stroke-current h-4 w-4 inline ml-2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                />
            </svg>
        </a>
    </nav>
    <!-- @endif -->
</template>

<script>
export default {
    props: ['dataSet'],
    data() {
        return {
            page: 1,
            prevUrl: false,
            nextUrl: false
        };
    },

    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.prevUrl = this.dataSet.prev_page_url;
            this.nextUrl = this.dataSet.next_page_url;
        },

        page() {
            this.broadcast().updateUrl();
        }
    },

    computed: {
        shouldPaginate() {
            return !!this.prevUrl || !!this.nextUrl;
        }
    },

    methods: {
        broadcast() {
            return this.$emit('changed', this.page);
        },

        updateUrl() {
            history.pushState(null, null, '?page=' + this.page);
        }
    }
};
</script>
