<template>
    {{ this.user }}
</template>

<script>
import { toRefs } from 'vue';
import { state as userState } from '@user';
import modal from '@components/modal';

export default {

    setup() {
        const { user } = toRefs(userState);

        return {
            user
        };
    },
    components: {
        modal
    },

    data() {
        return {
            isModalVisible: false
        };
    },

    methods: {
        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        }
    }
}
</script>
