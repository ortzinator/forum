<template>
    <div
        :id="'reply-' + this.data.id"
        class="ml-10 my-5 p-5 border bg-gray-100 border-gray-200 rounded-lg"
        v-cloak
    >
        <div class="flex mb-5 justify-between">
            <div>
                <a
                    :href="'/profile/' + data.user.name"
                    v-text="data.user.name"
                ></a>
                said <span v-text="ago"></span>
            </div>
            <favorite v-if="signedIn" :reply="data"></favorite>
        </div>

        <div class="mb-5" v-cloak>
            <div v-if="editing" class="v-cloak--hidden">
                <form @submit.prevent="update">
                    <textarea
                        name=""
                        id=""
                        rows="10"
                        class="w-full"
                        v-model="body"
                        required
                    ></textarea>
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
            <div v-else v-text="body"></div>
        </div>

        <!-- @can('update', $reply) -->
        <div v-if="!editing && canUpdate" class="flex space-x-2">
            <button
                @click="editing = true"
                class="bg-gray-400 px-2 py-1 text-white text-xs"
            >
                Edit
            </button>
            <button
                @click="destroy"
                class="bg-red-500 px-2 py-1 text-white text-xs"
            >
                Delete
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
    props: ['data'],
    data() {
        return {
            editing: false,
            body: this.data.body
        };
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        },

        canUpdate() {
            return this.authorize(user => this.data.user_id == user.id);
        },

        ago() {
            return moment(this.data.created_at).fromNow();
        }
    },

    methods: {
        update() {
            axios
                .patch('/replies/' + this.data.id, {
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
                .delete('/replies/' + this.data.id)
                .then(data => {
                    this.$emit('deleted', this.data.id);
                    flash('Your reply has been deleted');
                })
                .catch(error => {
                    flash(error.response.data, 'error');
                });
        }
    }
};
</script>
