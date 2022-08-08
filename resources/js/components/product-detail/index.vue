<template>
    <div class="product-detail-wrapper">
        <h2 class="product-name">{{ product.name }}</h2>
        <section class="product-detail-content">
            <div class="product-images">
                <img class="product-image--preview" :src="product.image?.image_path" alt=""/>
                <div class="product-images--available">
                    <img v-for="image in product.images" src="" alt=""/>
                </div>
            </div>
            <div class="product-description">
                <div class="product-price">
                    <h4 class="price">{{ product.price }}</h4>
                    <div class="app-btn app-btn-link cart-wrapper"
                         @click="onBuyClick">
                        <img class="cart-img" src="/icons/shopping-cart.svg" alt="">
                        <span>Buy</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import {computed, watch} from "vue";
import {useHttp} from "@/composables/useHttp";
import {useCart} from "@/composables/useCart";

const props = defineProps({
    id: {required: true}
});

const http = useHttp();
const { addCartItem, errors } = useCart();

// TODO: remove it by `useProduct(productId)`
const product = await computed(async () => (await http.get(`/api/product/${props.id}`)).product).value;

// TODO: add selection images
product['images'] = [1, 2, 3];

const onBuyClick = () => addCartItem(product.id);

watch(errors, () => console.log(errors))
</script>
