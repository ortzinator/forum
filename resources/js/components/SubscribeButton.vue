<template>
    <div>
        <button :class="classes" @click="subscribe" v-text="btnText"></button>
    </div>
</template>

<script>
export default {
    props: ['active'],
    data() {
        return {
            isActive: this.active
        };
    },
    computed: {
        classes() {
            return [
                'bg-gradient-to-b border border-gray-800 from-gray-200 px-2 py-1 to-gray-100',
                this.isActive ? 'from-blue-200' : 'from-gray-200'
            ];
        },
        btnText() {
            return this.isActive ? 'Unsubscribe' : 'Subscribe';
        }
    },
    methods: {
        subscribe() {
            let requestType = this.active ? 'delete' : 'post';
            axios[requestType](location.pathname + '/subscriptions');
            this.isActive = !this.isActive;

            flash('Subscribed');
        }
    }
};
</script>
