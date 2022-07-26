<template>
    <div class="body-field">
        <label>{{ label }}</label>

        <input
            :value="model"
            v-bind:type="type"
            v-bind:disabled="disabled"
            @input="$emit('update:model', $event.target.value)"
        >

        <p class="error-message"
           v-show="messages"
           v-for="(message) in messages"
        >
            {{ message }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            required: true,
            type: String
        },
        model: {
            default: null
        },
        type: {
            type: String,
            default: "text"
        },
        messages: {
            type: [Object, null],
            default: null
        },
        disabled: Boolean
    },

    watch: {
        model() {
            this.$emit('validate');
        }
    },

    emits: ['update:model', 'validate'],
}
</script>
