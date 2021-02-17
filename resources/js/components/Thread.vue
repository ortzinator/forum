<script>
import Replies from './Replies.vue';
import SubscribeButton from './SubscribeButton.vue';
export default {
    props: ['thread'],
    components: { Replies, SubscribeButton },

    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            editing: false,
            form: {
                title: this.thread.title,
                body: this.thread.body
            }
        };
    },

    methods: {
        toggleLock() {
            axios[this.locked ? 'delete' : 'post'](
                '/locked-threads/' + this.thread.slug
            );
            this.locked = !this.locked;
        },
        update() {
            let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
            axios.patch(uri, this.form).then(() => {
                flash('Your thread was updated');
                this.editing = false;
            });
        },
        cancel() {
            this.form = {
                title: this.thread.title,
                body: this.thread.body
            };
            this.editing = false;
        }
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    }
};
</script>
