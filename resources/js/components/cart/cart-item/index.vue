<template>
    <li class="cart-item">
        <div :property="product = item.product" class="item-wrapper">
            <section class="item-section">
                <img :src="product.image?.path" alt=""/>
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
                    <span class="">{{ item.quantity_price }} ₴</span>
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
import {debounce} from "@/shared/utils/debounce";

const props = defineProps({
    item: {
        required: true,
        type: Object
    }
});

const {updateCartItem, deleteCartItem} = useCart();

const commitChanges = debounce((item) => updateCartItem(item.id, {quantity: item.quantity}), 700);

const increaseQuantity = (cartItem) => {
    cartItem.quantity++;

    commitChanges(cartItem);
};

const decreaseQuantity = (cartItem) => {
    if (cartItem.quantity > 1) {
        cartItem.quantity--;
        commitChanges(cartItem);
    }
};
</script>
