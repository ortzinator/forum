<script>
import Replies from './Replies.vue';
import SubscribeButton from './SubscribeButton.vue';
export default {
    props: ['initRepliesCount'],
    components: { Replies, SubscribeButton },

    data() {
        return {
            repliesCount: this.initRepliesCount
        };
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        }
    }
};
</script>
