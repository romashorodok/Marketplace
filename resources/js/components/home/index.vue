<template>

</template>

<script>
import modal from '../modal';

export default {
    name: 'App',
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
