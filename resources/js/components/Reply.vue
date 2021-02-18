<template>
    <div
        :id="'reply-' + this.id"
        class="ml-10 my-5 p-5 border bg-gray-100 border-gray-200 rounded-lg"
        :class="isBest ? 'bg-blue-50' : ''"
        v-cloak
    >
        <div class="flex mb-5 justify-between">
            <div>
                <a
                    :href="'/profile/' + reply.user.name"
                    v-text="reply.user.name"
                ></a>
                said <span v-text="ago"></span>
            </div>
            <favorite v-if="signedIn" :reply="reply"></favorite>
        </div>

        <div class="mb-5" v-cloak>
            <div v-if="editing" class="v-cloak--hidden">
                <form @submit.prevent="update">
                    <!-- <textarea
                        name=""
                        id=""
                        rows="10"
                        class="w-full"
                        v-model="body"
                        required
                    ></textarea> -->
                    <wysiwyg
                        name="body"
                        v-model="body"
                        placeholder="What do you want to say?"
                    ></wysiwyg>
                    <button
                        @click="editing = false"
                        class="bg-gray-400 px-2 py-1 text-white text-xs"
                        type="button"
                    >
                        Cancel
                    </button>
                    <button class="bg-blue-400 px-2 py-1 text-white text-xs">
                        Submit
                    </button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <!-- @can('update', $reply) -->
        <div class="flex justify-between">
            <div class="flex space-x-2">
                <button
                    @click="editing = true"
                    class="bg-gray-400 px-2 py-1 text-white text-xs"
                    v-if="!editing && authorize('owns', reply)"
                >
                    Edit
                </button>
                <button
                    @click="destroy"
                    class="bg-red-500 px-2 py-1 text-white text-xs"
                    v-if="!editing && authorize('owns', reply)"
                >
                    Delete
                </button>
            </div>
            <button
                @click="markBestReply"
                class="bg-blue-500 px-2 py-1 text-white text-xs"
                v-show="!isBest"
                v-if="authorize('owns', reply.thread)"
            >
                Best Reply
            </button>
        </div>
        <!-- @endcan -->
    </div>
</template>
<script>
import Favorite from './Favorite.vue';
import moment from 'moment';
export default {
    components: { Favorite },
    props: ['reply'],
    data() {
        return {
            editing: false,
            body: this.reply.body,
            isBest: this.reply.isBest,
            id: this.reply.id
        };
    },

    computed: {
        ago() {
            return moment(this.reply.created_at).fromNow() + '...';
        }
    },
    created() {
        window.events.$on('best-reply-selected', id => {
            this.isBest = id === this.id;
        });
    },

    methods: {
        update() {
            axios
                .patch('/replies/' + this.id, {
                    body: this.body
                })
                .then(data => {
                    this.editing = false;
                    flash('Your reply was updated');
                })
                .catch(error => {
                    flash(error.response.data, 'error');
                });
        },

        destroy() {
            axios
                .delete('/replies/' + this.id)
                .then(data => {
                    this.$emit('deleted', this.id);
                    flash('Your reply has been deleted');
                })
                .catch(error => {
                    flash(error.response.data, 'error');
                });
        },
        markBestReply() {
            axios.post('/replies/' + this.id + '/best');
            window.events.$emit('best-reply-selected', this.id);
        }
    }
};
</script>
