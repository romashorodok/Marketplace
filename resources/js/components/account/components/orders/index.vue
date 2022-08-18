<template>
    <div class="order-wrapper">
        <div class="order-list">
            <ul class="list-wrapper">
                <app-order v-for="order in orders" :order="order"/>
            </ul>
        </div>
        <div class="order-pagination"></div>
    </div>
</template>

<script setup>
import {useOrders} from "@/composables/useOrders";
import {onMounted, watch} from "vue";
import AppOrder from './order';

const {fetchOrders, orders} = useOrders();

onMounted(() => fetchOrders());

watch(orders, console.log);
</script>
