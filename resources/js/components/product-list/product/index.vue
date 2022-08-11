<template>
    <div class="card-wrapper" @click="productDetailShow">
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

        <p class="card-price">{{ product.price }} ₴</p>
    </div>
</template>

<script setup>
import {useRouter} from "vue-router";

const props = defineProps({
    product: {required: true, type: Object}
});

const router = useRouter();

const productDetailShow = () => {
    router.push({name: 'product-detail', params: {id: props.product.id}});
}
</script>
