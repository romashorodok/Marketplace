<template>
    <textarea
        :value="text"
        :maxlength="size"
        :placeholder="placeholder"
        rows="10"
        cols="30"
        @input="value => emit('update:text', value.target.value)"/>
</template>

<script setup>
import {defineProps, defineEmits} from "vue";

defineProps({
    text: {type: String},
    size: {type: Number, default: 500},
    placeholder: {type: String}
});

const emit = defineEmits(['update:text']);
</script>

<style>
textarea {
    resize: none;
}
</style>
