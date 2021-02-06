<template>
    <div>
        <form
            v-if="canUpdate"
            method="POST"
            action=""
            enctype="multipart/form-data"
        >
            <input
                type="file"
                name="avatar"
                accept="image/*"
                @change="onChange"
            />
        </form>

        <div><img :src="avatar" alt="" /></div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            avatar: this.user.avatar_path
        };
    },

    computed: {
        canUpdate() {
            return this.authorize(user => user.id === this.user.id);
        }
    },

    methods: {
        onChange(e) {
            if (!e.target.files.length) return;

            let avatar = e.target.files[0];

            let reader = new FileReader();

            reader.readAsDataURL(avatar);

            reader.onload = e => {
                this.avatar = e.target.result;
            };

            this.persist(avatar);
        },

        persist(avatar) {
            let data = new FormData();

            data.append('avatar', avatar);
            axios
                .post(`/api/users/${this.user.name}/avatar`, data)
                .then(() => flash('Avatar was uploaded'));
        }
    }
};
</script>
