<template>
    <nav
        role="navigation"
        aria-label="Pagination Navigation"
        class="flex items-center justify-between"
        v-if="shouldPaginate"
    >
        <div class="flex justify-between flex-1">
            <a
                href="#"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                v-show="prevUrl"
                @click.prevent="page--"
            >
                Previous
            </a>
            <a
                href="#"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                v-show="nextUrl"
                @click.prevent="page++"
            >
                Next
            </a>
        </div>
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
