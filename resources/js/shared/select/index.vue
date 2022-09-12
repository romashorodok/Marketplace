<template>
    <select :value="model" @change="emits('update:model', $event.target.value)">
        <option value="">--Please choose an option--</option>
        <option v-for="option in options" :value="option.name">
            {{ option.name }}
        </option>
    </select>
    <template v-for="message in messages">
        <p class="error-message" v-if="message instanceof Array" v-for="_message in message">
            {{ _message }}
        </p>
        <p class="error-message" v-else>
            {{ message }}
        </p>
    </template>
</template>

<script setup>

import {defineEmits, defineProps} from "vue";

const props = defineProps({
    options: {required: true, type: Array},
    model: {required: true},
    messages: {
        type: [Object, null],
        default: null
    },
});
const emits = defineEmits(['update:model']);
</script>

<style>
select {
    margin-top: 15px;
}
</style>
