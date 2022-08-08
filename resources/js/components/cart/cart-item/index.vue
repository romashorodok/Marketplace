<template>
    <li class="cart-item">
        <div :property="product = item.product" class="item-wrapper">
            <section class="item-section">
                <img :src="product.image?.image_path" alt=""/>
                <div class="item-section--spec">
                    <span>{{ product.name }}</span>
                    <div class="spec-list">
                        <!-- TODO: add product specs -->
                        <span class="spec-item">Gray</span>
                        <span class="spec-item">8GB</span>
                        <span class="spec-item">1TB</span>
                    </div>
                </div>
            </section>
            <section class="item-section">
                <div class="item-section--price">
                    <span class="">{{ item.price }} ₴</span>
                    <span class="price-qty">Qty: {{ item.quantity }} </span>
                    <div class="qty-actions">
                        <span class="app-btn-link" @click="decreaseQuantity(item)">-</span>
                        <span class="app-btn-link" @click="increaseQuantity(item)">+</span>
                    </div>
                </div>
                <div class="item-section--actions">
                    <img class="item-remove" src="/icons/close.svg" alt="close" @click="deleteCartItem(item.id)"/>
                </div>
            </section>
        </div>
    </li>
</template>

<script setup>
import {useCart} from "@/composables/useCart";

const props = defineProps({
    item: {
        required: true,
        type: Object
    }
});

const {updateCartItem, deleteCartItem} = useCart();

const increaseQuantity = (cartItem) => {
    const quantity = cartItem.quantity;

    updateCartItem(cartItem.id, {quantity: quantity + 1});
};

const decreaseQuantity = (cartItem) => {
    const quantity = cartItem.quantity;

    if (quantity <= 1)
        return;

    updateCartItem(cartItem.id, {quantity: quantity - 1})
};
</script>
