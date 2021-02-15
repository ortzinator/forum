<template>
    <div>
        <div v-for="(reply, index) in items" v-bind:key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <div id="reply-form" class="my-5 p-5 border border-gray-200 rounded-lg">
            <new-reply @created="add"></new-reply>
        </div>
    </div>
</template>

<script>
import Reply from './Reply.vue';
import NewReply from './NewReply.vue';
import collection from '../mixins/collection';

export default {
    components: { reply: Reply, NewReply },
    mixins: [collection],
    props: ['data'],

    data() {
        return {
            dataSet: false
        };
    },

    created() {
        this.fetch();
    },

    methods: {
        fetch(page) {
            axios.get(this.url(page)).then(this.refresh);
        },

        url(page) {
            if (!page) {
                let query = new URLSearchParams(window.location.search).get(
                    'page'
                );
                page = query ? query : 1;
            }
            return `${location.pathname}/replies?page=${page}`;
        },

        refresh({ data }) {
            this.dataSet = data;
            this.items = data.data;

            window.scrollTo(0, 0);
        }
    }
};
</script>
