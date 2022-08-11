<template>
    <div class="card-wrapper">
        <img v-if="product.image?.path"
             :src="product.image?.path"
             class="card-image"
             loading="lazy"
             alt="">
        <a v-else class="card-image--grey"/>

        <a class="app-btn-link card-name">
            {{ product.name }}
        </a>

        <p v-show="product.category?.name">
            Category: {{ product.category?.name }}
        </p>

        <p class="card-price"> {{ product.price }} </p>
    </div>
</template>

<script setup>
const props = defineProps({
    product: {required: true, type: Object}
});
</script>
