export default {
    data() {
        return {
            items: []
        };
    },
    methods: {
        remove(index) {
            this.items.splice(index, 1);
            this.$emit('removed');
        },

        add(reply) {
            this.items.push(reply);
            this.$emit('added');
        }
    }
};
