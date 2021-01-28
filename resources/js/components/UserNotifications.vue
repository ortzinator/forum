<template>
    <dropdown v-if="notifications">
        <template v-slot:trigger>
            <button class="flex items-center mx-5">
                <svg
                    class="stroke-current h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    />
                </svg>
            </button>
        </template>
        <ul class="p-5 space-y-2">
            <li v-for="notif in notifications">
                <a
                    class="underline"
                    :href="notif.data.link"
                    v-text="notif.data.message"
                    @click="markAsRead(notif)"
                ></a>
            </li>
        </ul>
    </dropdown>
</template>
<script>
export default {
    data() {
        return {
            notifications: false
        };
    },

    created() {
        axios
            .get('/profile/' + window.App.user.name + '/notifications')
            .then(response => (this.notifications = response.data));
    },

    methods: {
        markAsRead(notif) {
            axios.delete(
                '/profile/' +
                    window.App.user.name +
                    '/notifications/' +
                    notif.id
            );
        }
    }
};
</script>
