<template>
    <div>
        <div class="upload-preview"
             @click="uploadBtn.click()"
             @mouseover="showUploadText = true"
             @mouseleave="showUploadText = false">
            <img :src="preview" height="180" alt=""/>
            <span v-show="showUploadText" class="upload-text">Click to change</span>
        </div>
        <input ref="uploadBtn" v-show="false" type="file" @input="changeImage" accept="image/*"/>
        <template v-for="message in messages">
            <p class="error-message" v-if="message instanceof Array" v-for="_message in message">
                {{ _message }}
            </p>
            <p class="error-message" v-else>
                {{ message }}
            </p>
        </template>
    </div>
</template>

<script setup>
import {defineEmits, defineProps, ref} from "vue";

const props = defineProps({
    messages: {type: Object},
    image: {}
});

const showUploadText = ref(false);
const uploadBtn = ref(null);
const image = ref(null);
const preview = ref(props.image ?? null);
const emits = defineEmits(['upload']);

const changeImage = (event) => {
    image.value = event.target.files[0];
    emits('upload', image.value);
    preview.value = URL.createObjectURL(image.value);
};
</script>

<style lang="scss">
.upload-preview {
    display: flex;
    position: relative;
    width: 180px;
    cursor: pointer;

    .upload-text {
        position: absolute;
        top: 45%;
        left: 20%;
        user-select: none;
        color: #ef8374;
    }

    img {
        width: 100%;
    }
}
</style>
