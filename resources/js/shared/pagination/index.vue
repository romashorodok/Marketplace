<template>
    <div class="pagination-wrapper">
        <template v-for="page in pages">
            <a class="app-btn"
               :class="page.number === currentPage ? 'app-btn-active' : '' "
               @click="onChangePage(page.number)">
                {{ page.number }}
            </a>
        </template>
    </div>
</template>

<script setup>
import {defineEmits, defineProps} from "vue";

const props = defineProps({
    pages: {type: Array, required: true},
    currentPage: {type: Number, required: true}
});

const emits = defineEmits([
    'onChange'
]);

const onChangePage = (pageNumber) => {
    if (props.currentPage !== pageNumber)
        emits('onChange', pageNumber);
};
</script>
