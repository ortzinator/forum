<template>
    <div>
        <!-- @auth -->
        <div v-if="signedIn">
            <label for="body" hidden>Reply:</label>
            <textarea
                class="w-full mb-5"
                name="body"
                id="body"
                rows="5"
                placeholder="What do you want to say?"
                v-model="body"
            ></textarea>
            <button
                class="bg-gray-400 hover:bg-blue-500 text-white rounded-lg px-5 py-2"
                @click="addReply"
            >
                Post
            </button>
        </div>
        <!-- @endauth -->
        <!-- @guest -->
        <p v-else class="text-center">
            Please <a href="/login" class="underline">sign in</a> to reply to
            this thread
        </p>
        <!-- @endguest -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            body: ''
        };
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    },

    methods: {
        addReply() {
            axios
                .post(location.pathname + '/replies', { body: this.body })
                .then(response => {
                    this.body = '';

                    flash('Your reply has been posted');

                    this.$emit('created', response.data);
                });
        }
    }
};
</script>
