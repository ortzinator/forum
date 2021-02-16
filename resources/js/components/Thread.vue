<script>
import Replies from './Replies.vue';
import SubscribeButton from './SubscribeButton.vue';
export default {
    props: ['thread'],
    components: { Replies, SubscribeButton },

    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked
        };
    },

    methods: {
        toggleLock() {
            axios[this.locked ? 'delete' : 'post'](
                '/locked-threads/' + this.thread.slug
            );
            this.locked = !this.locked;
        }
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    }
};
</script>
