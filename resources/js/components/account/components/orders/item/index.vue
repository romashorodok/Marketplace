<template>
    <li class="order-item">
        <section class="order-item-section">
            <img class="product-image" :src="item.product.image.path" :alt="item.product.name"/>
        </section>
        <section class="order-item-section order-item--info">
            <a class="order-item--name app-btn-link">
                {{ item.product.name }}
            </a>
        </section>
        <section class="order-item-section order-item--info">
            <p class="order-item-section--heading">
                Price
            </p>
            <p> {{ item.price }} ₴</p>
        </section>
        <section class="order-item-section order-item--info">
            <p class="order-item-section--heading">
                Quantity
            </p>
            <p class="order-item--quantity">{{ item.quantity }}</p>
        </section>
        <section class="order-item-section order-item--info">
            <p class="order-item-section--heading">
                Sum
            </p>
            <p> {{ item.quantity_price }} ₴</p>
        </section>
    </li>
</template>

<script setup>
import {defineProps} from "vue";

const props = defineProps({
    item: { required: true, type: Object}
});
</script>
