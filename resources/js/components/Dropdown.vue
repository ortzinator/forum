<template>
    <div class="dropdown relative" v-cloak>
        <div
            class="dropdown-trigger"
            @click.prevent="isOpen = !isOpen"
            aria-haspopup="true"
            :aria-expanded="isOpen"
        >
            <slot name="trigger"></slot>
        </div>

        <div v-show="isOpen" v-cloak>
            <ul
                v-show="isOpen"
                class="dropdown-menu absolute bg-gray-100 mt-2 rounded shadow-lg text-gray-900 z-10 v-cloak--hidden"
                :class="classes"
                v-cloak
            >
                <slot></slot>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Dropdown',
    props: ['classes'],
    data() {
        return {
            isOpen: false
        };
    },
    watch: {
        isOpen(isOpen) {
            if (isOpen) {
                document.addEventListener('click', this.closeIfClickedOutside);
            }
        }
    },
    methods: {
        closeIfClickedOutside(event) {
            if (!event.target.closest('.dropdown')) {
                this.isOpen = false;
                document.removeEventListener(
                    'click',
                    this.closeIfClickedOutside
                );
            }
        }
    }
};
</script>
