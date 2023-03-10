<template>
    <div class="checkout-wrapper">
        <form @submit.prevent="submitPayment">
            <app-field label="First name"
                       v-model:model="schema.firstName.value"
                       :messages="schema.firstName.messages"/>
            <app-field label="Last name"
                       v-model:model="schema.lastName.value"
                       :messages="schema.lastName.messages"/>
            <app-field label="Address"
                       v-model:model="schema.address.value"
                       :messages="schema.address.messages"/>

            <div class="checkout-card-helper">
                <p>valid card: 4242424242424242</p>
                <p>invalid card: 4000000000000002</p>
                <p>exp date: any feature</p>
                <p>cvc: any three numbers</p>
            </div>
            <div class="body-field">
                <label>Card</label>
                <div ref="cardWrapper" class="checkout-field">
                    <div ref="cardRef"></div>
                </div>
                <template v-for="message in schema.card.messages">
                    <p class="error-message" v-for="_message in message">
                        {{ _message }}
                    </p>
                </template>
            </div>

            <app-button v-if="active" ref="submitButton" class="buy-button">
                Pay {{ cart.total_price }} ₴
            </app-button>
        </form>
    </div>
</template>

<script setup>
import {loadStripe} from '@stripe/stripe-js';
import {STRIPE} from "@/env";
import {onMounted, ref, watch} from "vue";
import AppButton from '@/shared/button';
import AppField from '@/shared/field';
import {defSchema, useForm} from "@/composables/useForm";
import {schemaField} from "@models/schema";
import {useAccount} from "@/composables/useAccount";
import {useHttp} from "@/composables/useHttp";
import {useCart} from "@/composables/useCart";
import {useRouter} from "vue-router";

const cardRef = ref();
const cardWrapper = ref();
const active = ref(true);
const submitButton = ref(null);

const stripe = await loadStripe(STRIPE);
const stripeElements = stripe.elements();
const stripeCard = stripeElements.create('card', {hidePostalCode: true});

const {account, fetchAccount} = useAccount();
const {fetchCart, cart} = useCart();
const router = useRouter();
const http = useHttp();

await fetchAccount();

const schema = defSchema({
    firstName: schemaField(account.value?.firstName).name(),
    lastName: schemaField(account.value?.lastName).name(),
    address: schemaField().must(),

    card: schemaField()
});

const messages = {
    firstName: {regex: 'Enter valid first name.'},
    lastName: {regex: 'Enter valid last name.'},
    address: {required: 'Enter address.'}
};

const {validate, hasError, setServerErrors, resetServerErrors} = useForm(schema, messages);

watch(schema, () => validate());
watch(http.errors, () => active.value = true);

onMounted(() => fetchCart());
onMounted(() => stripeCard.mount(cardRef.value));
onMounted(() => {
    stripeCard.on('focus', () => {
        cardWrapper.value.classList.add('stripe-wrapper--focus')
    });
    stripeCard.on('blur', () => {
        cardWrapper.value.classList.remove('stripe-wrapper--focus')
    });
});

const submitPayment = async () => {
    validate();
    await (async () => active.value = false)();
    const hidden = submitButton.value === null;

    if (!hasError() && hidden) {
        const {token} = await stripe.createToken(stripeCard);

        if (!token) {
            setServerErrors({card: ['Enter valid card']})
            active.value = true;
            return;
        } else {
            resetServerErrors();
        }

        const result = await http.post('/api/checkout', {
            firstName: schema.firstName.value,
            lastName: schema.lastName.value,
            address: schema.address.value,
            paymentToken: token.id
        });

        if (result?.charge_token) {
            await fetchCart();
            toInvoice(result.charge_token);
            return;
        }

        const errors = http.errors.value?.response.data?.errors;

        setServerErrors(errors);
    }

    active.value = true;
};

const toInvoice = () => {
    router.replace({name: 'orders'});
};
</script>
