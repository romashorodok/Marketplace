<template>
    <button ref="buttonRef" :disabled="disabled" class="app-btn" @click="animate">
        <slot/>
        <transition-group @after-enter="animateEnd">
            <span class="ripple"
                  v-for="(item, index) in ripples"
                  v-show="item?.active"
                  :key="index"
                  :style="{'top': item.y + 'px', 'left': item.x + 'px'}">
            </span>
        </transition-group>
    </button>
</template>

<script setup>
import {defineProps, ref} from "vue";

const props = defineProps({
    type: {type: String, default: 'submit'},
    disabled: {type: Boolean, default: false}
});

const buttonRef = ref();
const ripples = ref([]);

const animate = (event) => {
    const position = buttonRef.value.getBoundingClientRect();

    ripples.value.push({
        x: event.clientX - position.left,
        y: event.clientY - position.top,
        active: true
    })
};

const animateEnd = (item) => item.remove();
</script>
