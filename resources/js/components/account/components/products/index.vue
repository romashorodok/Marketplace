<template>
    <div class="product-section">
        <div class="product-section-active">
            <router-link class="app-btn app-btn-link app-btn--inline" to="products/edit">
                <img class="add-img" src="/icons/add.svg" width="30" height="25" alt=""/>
                Add
            </router-link>
        </div>
        <div>
            <ul v-if="products.length !== 0" class="product-wrapper">
                <li class="product-card product-action" v-for="product in products.data">
                    <app-product :product="product"/>
                    <router-link
                        class="app-btn app-btn-link app-btn--inline product-edit"
                        :to="{ name: 'editById', params: { id: product.id, create: false }}">
                        <img class="add-img" src="/icons/edit.svg" width="30" height="25" alt=""/>
                        Edit
                    </router-link>
                </li>
            </ul>
            <div v-else class="empty-section">
                <img src="/icons/cube.svg" width="40" alt=""/>
                <p>You don't have any products</p>
            </div>
        </div>
    </div>
</template>

<script setup>

import {onMounted} from "vue";
import {useAccount} from "@/composables/useAccount";
import AppProduct from "@components/product-list/product";

const {fetchProducts, products} = useAccount();

onMounted(() => fetchProducts());
</script>

<style lang="scss">
.product-action {
    position: relative;

    .product-edit {
        position: absolute;
        bottom: 0;
        right: 10px;
    }
}
</style>
