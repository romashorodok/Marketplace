<template>
    <div class="expansion-panel-wrapper">
        <div class="expansion-panel--header" @click="toggle">
            <div class="header--title">
                <slot name="title"/>
            </div>
            <div class="header--description" v-show="!visible">
                <slot name="description"/>
                <img src="" alt=""/>
            </div>

            <img class="expansion-panel--state" :src="visible ? upIcon : downIcon " alt="visible-state"/>
        </div>
        <div v-show="visible" class="expansion-panel--content">
            <slot name="content"/>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";

const visible = ref(false);

const upIcon = '/icons/up-outline.svg';
const downIcon = '/icons/down-outline.svg';

const toggle = () => visible.value = !visible.value;
</script>
