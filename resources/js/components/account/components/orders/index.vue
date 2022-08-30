<template>
    <div class="order-wrapper">
        <div class="order-list">
            <ul v-if="orders.length !== 0" class="list-wrapper">
                <app-order v-for="order in orders" :order="order"/>
            </ul>
            <div v-else class="empty-section">
                <img src="/icons/orders-outline.svg" width="40" alt="test"/>
                <p>You don't have any orders</p>
            </div>
        </div>
        <app-pagination :current-page="page" :pages="pages" @onChange="changePage"/>
    </div>
</template>

<script setup>
import {useOrders} from "@/composables/useOrders";
import {onMounted} from "vue";
import AppOrder from './order';
import AppPagination from '@/shared/pagination';

const {fetchOrders, setOrderPage, orders, pages, page} = useOrders();

onMounted(() => fetchOrders());

const changePage = (pageNumber) => setOrderPage(pageNumber);
</script>
