<template>
    <li class="list-item">
        <div class="body-field">
            <label>{{ label }}</label>
            <input v-model="model"
                   v-bind:type="type"
                   v-bind:disabled="disabled"
                   @blur="update"
            >
            <p v-show="message" class="error-message">
                {{ message }}
            </p>
        </div>
    </li>
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
        message: {
            type: [String, null],
            default: null
        },
        disabled: Boolean
    },
    methods: {
        update() {
            this.$emit('on-change', this.model);
        }
    },
}
</script>
