<template>
    <div class="order-wrapper">
        <div class="order-list">
            <ul class="list-wrapper">
                <app-order v-for="order in orders" :order="order"/>
            </ul>
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
