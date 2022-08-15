<template>
    <div class="checkout-wrapper">
        <form @submit.prevent="submitPayment">
            <app-field label="First name" />
            <app-field label="Last name" />
            <app-field label="Email" />

            <div ref="cardWrapper" class="checkout-field">
                <div ref="cardRef"></div>
            </div>
            <app-button>Submit</app-button>
        </form>
    </div>
</template>

<script setup>
import {loadStripe} from '@stripe/stripe-js';
import {STRIPE} from "@/env";
import {onMounted, ref} from "vue";
import AppButton from '@/shared/button';
import AppField from '@/shared/field';

const cardRef = ref();
const cardWrapper = ref();

const stripe = await loadStripe(STRIPE);
const stripeElements = stripe.elements();
const stripeCard = stripeElements.create('card', {hidePostalCode: true});

onMounted(() => stripeCard.mount(cardRef.value));
onMounted(() => {
    stripeCard.on('focus', () => {
        cardWrapper.value.classList.add('stripe-wrapper--focus')
    });
    stripeCard.on('blur', () => {
        cardWrapper.value.classList.remove('stripe-wrapper--focus')
    })
});

const submitPayment = () => {
    console.log(stripe.createToken(stripeCard))
};
</script>
