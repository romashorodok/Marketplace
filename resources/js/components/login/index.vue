<template>
    <app-modal>
        <template v-slot:modal-header>
            <h3 class="modal-header-name">Login</h3>
        </template>

        <template v-slot:modal-body>
            <section class="modal-login-auth">
                <form @submit.prevent="checkForm">
                    <app-field label="Email"
                               v-model:model="schema.email.value"
                               :type="'email'"
                               :messages="schema.email.messages"/>
                    <app-field label="Password"
                               v-model:model="schema.password.value"
                               :type="'password'"
                               :messages="schema.password.messages"/>
                    <button class="login-confirm app-btn" type="submit">
                        Login
                    </button>
                </form>
            </section>
            <section class="modal-login-oauth">
                <span class="oauth-divider">or</span>
                <button class="google-confirm app-btn">
                    Google
                </button>
            </section>
            <section class="modal-login-action">
                <a class="app-btn-link"
                   @click="redirectRegister">
                    Register
                </a>
                <a class="app-btn-link">
                    Forgot password ?
                </a>
            </section>
        </template>
    </app-modal>
</template>

<script setup>
import {defSchema, useForm} from "@/composables/useForm";
import {schemaField} from "@/models/schema";
import {watch} from "vue";
import AppField from '@/shared/field';
import AppModal from '@/shared/modal';
import {useStore} from "vuex";


const schema = defSchema({
    email: schemaField().email(),
    password: schemaField().password()
});

const messages = {
    email: {regex: 'Enter valid email'},
    password: {regex: 'Minimum eight characters, one letter, one number'}
};


const {validate, hasError, resetServerErrors, setServerErrors} = useForm(schema, messages);
const store = useStore();

watch(schema, validate);

const checkForm = () => {
    validate();

    if (!hasError()) {
        resetServerErrors();
        store.dispatch('login', {
            email: schema.email.value,
            password: schema.password.value
        })
            .catch(({response}) => {
                setServerErrors(response.data.errors);
            });
    }
};
</script>
