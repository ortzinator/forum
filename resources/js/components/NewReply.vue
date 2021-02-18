<template>
    <div>
        <!-- @auth -->
        <div v-if="signedIn">
            <label for="body" hidden>Reply:</label>
            <wysiwyg
                name="body"
                v-model="body"
                placeholder="What do you want to say?"
            ></wysiwyg>
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
import Tribute from 'tributejs';
export default {
    data() {
        return {
            body: ''
        };
    },

    mounted() {
        let tribute = new Tribute({
            // column to search against in the object (accepts function or string)
            lookup: 'value',
            // column that contains the content to insert by default
            fillAttr: 'value',
            values: _.debounce(function(query, cb) {
                axios
                    .get('/api/users', { params: { name: query } })
                    .then(function(response) {
                        // console.log(response);
                        cb(response.data);
                    });
            }, 400)
        });
        tribute.attach(document.querySelectorAll('#body'));
    },

    methods: {
        addReply() {
            axios
                .post(location.pathname + '/replies', { body: this.body })
                .then(response => {
                    this.body = '';

                    flash('Your reply has been posted');

                    this.$emit('created', response.data);
                })
                .catch(error => {
                    flash(error.response.data, 'error');
                });
        }
    },
    watch: {
        body(value) {
            // console.log(value);
        }
    }
};
</script>
