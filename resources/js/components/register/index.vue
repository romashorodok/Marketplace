<template>
    <app-modal>
        <template v-slot:modal-header>
            <h3 class="modal-header-name">
                Sign Up
            </h3>
        </template>
        <template v-slot:modal-body>
            <form @submit.prevent="checkForm">
                <app-field label="First name"
                           v-model:model="schema.firstName.value"
                           :messages="schema.firstName.messages"/>

                <app-field label="Last name"
                           v-model:model="schema.lastName.value"
                           :messages="schema.lastName.messages"/>

                <app-field label="Email"
                           v-model:model="schema.email.value"
                           :type="'email'"
                           :messages="schema.email.messages"/>

                <app-field label="Password"
                           v-model:model="schema.password.value"
                           :type="'password'"
                           :messages="schema.password.messages"/>

                <app-field label="Password confirmation"
                           v-model:model="schema.passwordConfirm.value"
                           :type="'password'"
                           :messages="schema.passwordConfirm.messages"/>

                <button class="modal-register-confirm app-btn"
                        type="submit">
                    Submit
                </button>
            </form>
            <a class="modal-register-margin app-btn-link--right"
               @click="toLogin">
                Log In
            </a>
        </template>
    </app-modal>
</template>

<script setup>
import {watch} from "vue";
import {useStore} from "vuex";
import {defSchema, useForm} from "@/composables/useForm";
import AppModal from '@/shared/modal';
import AppField from '@/shared/field';
import {schemaField} from "@/models/schema";

const schema = defSchema({
    firstName: schemaField().name(),
    lastName: schemaField().name(),
    email: schemaField().email(),
    password: schemaField().password(),
    passwordConfirm: schemaField()
        .password()
        .same(() => schema?.password.value)
});

const messages = {
    firstName: {regex: 'First name required'},
    lastName: {regex: 'Last name required'},
    email: {regex: 'Enter valid email'},
    password: {regex: 'Minimum eight characters, one letter, one number'},
    passwordConfirm: {
        regex: 'Minimum eight characters, one letter, one number',
        same: 'Password must be same'
    }
};

const {validate, hasError, setServerErrors, resetServerErrors} = useForm(schema, messages);
const store = useStore();

watch(schema, validate);

const checkForm = () => {
    validate();

    if (!hasError()) {
        resetServerErrors();
        store.dispatch('register', {
            firstName: schema.firstName.value,
            lastName: schema.lastName.value,
            password: schema.password.value,
            email: schema.email.value
        })
            .then(() => store.commit('changeModal', 'closed'))
            .catch(({response}) => {
                setServerErrors(response.data.errors);
            });
    }
};
const toLogin = () => store.commit('changeModal', 'login');
</script>
