<template>
    <li class="order">
        <app-expansion-panel>
            <template v-slot:title>
                <div class="order-heading--status">
                    <span class="order-status--decoration" style="background: green"/>

                    <p class="order-heading--name">
                        № {{ order.charge_token }}
                    </p>
                    <p class="order-heading--date">
                        In {{ order.created_at }}
                    </p>
                    <p class="order-status--value">
                        Complete
                    </p>
                </div>
            </template>
            <template v-slot:description>
                <p class="order-amount--name"> Order amount </p>
                <p class="order-amount--value">
                    {{ order.total_price }} ₴
                </p>
            </template>
            <template v-slot:content>
                <div class="order-content">
                    <div class="order-description">
                        <section class="order-content--section">
                            <p class="section-heading">Total:</p>
                            <p> {{ order.total_price }} ₴</p>
                        </section>
                        <section class="order-content--section">
                            <p class="section-heading">Receiver:</p>
                            <p> {{ order.firstName }} {{ order.lastName }}</p>
                            <p> {{ order.email }}</p>
                        </section>
                        <section class="order-content--section">
                            <p class="section-heading">Address:</p>
                            <p> {{ order.address }}</p>
                        </section>
                    </div>
                    <div class="order-content--items">
                        <ul class="order-item--list">
                            <order-item v-for="item in order.billing_items" :item="item"/>
                        </ul>
                    </div>
                </div>
            </template>
        </app-expansion-panel>
    </li>
</template>

<script setup>
import {defineProps} from "vue";
import AppExpansionPanel from "@/shared/expansion-panel";
import OrderItem from "../item";

const props = defineProps({
    order: {required: true, type: Object}
});
</script>
