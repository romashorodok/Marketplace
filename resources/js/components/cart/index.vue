<template>
    <div v-if="!cartItems" class="cart-empty">
        <img class="empty-img" src="/icons/shopping-bag.svg" alt="empty"/>
        <h4>Nothing here</h4>
    </div>
    <div v-else class="cart-content">
        <span class="cart-delete-products">
            <img src="/icons/close.svg" alt="close" @click="deleteCartItems"/>
        </span>
        <ul class="cart-list">
            <li v-if="!cartItems"> Add some items to cart</li>
            <app-cart-item v-else v-for="item in cartItems" :item="item"/>
        </ul>
        <div class="cart-detail">
            <div class="cart-detail-info">
                <div class="info-total">
                    <h4>Total</h4>
                    <h4>{{ cart?.total_price }} ₴</h4>
                </div>
                <button class="app-btn">Continue</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted} from "vue";
import {useCart} from "@/composables/useCart";
import AppCartItem from './cart-item';

const {fetchCart, cart, deleteCartItems} = useCart();

const cartItems = computed(() => {
    const items = cart.value?.billing_items ?? [];
    const empty = items.length === 0;

    return empty ? null : items;
});

onMounted(async () => await fetchCart());
</script>
