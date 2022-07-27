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
                           v-model:model="firstName"
                           v-model:messages="messages.firstName"
                           v-on:validate="firstNameValidator.validate" />

                <app-field label="Last name"
                           v-model:model="lastName"
                           v-model:messages="messages.lastName"
                           v-on:validate="lastNameValidator.validate" />

                <app-field label="Email"
                           v-model:model="email"
                           v-model:messages="messages.email"
                           :type="'email'"
                           v-on:validate="emailValidator.validate" />

                <app-field label="Password"
                           v-model:model="password"
                           v-model:messages="messages.password"
                           :type="'password'"
                           v-on:validate="passwordValidator.validate" />

                <app-field label="Password confirmation"
                           v-model:model="passwordConfirm"
                           v-model:messages="messages.passwordConfirm"
                           :type="'password'"
                           v-on:validate="passwordConfirmValidator.validate" />

                <button class="modal-register-confirm app-btn"
                        type="submit">
                    Submit
                </button>
            </form>
            <a class="modal-register-margin app-btn-link--right"
               @click="redirectLogin()">
                Log In
            </a>
        </template>
    </app-modal>
</template>

<script>
import {mapActions, mapMutations} from "vuex";
import AppModal from '@/shared/modal';
import AppField from '@/shared/field';
import useValidator from "@/shared/hooks/useValidator";
import {
    isValidMessages,
    addServerMessageErrors,
    deleteServerMessageErrors,
} from "@/shared/hooks/useValidator";

export default {
    data: () => ({
        firstName: null,
        lastName: null,
        password: null,
        passwordConfirm: null,
        email: null,

        messages: { },
    }),

    computed: {
        firstNameValidator() {
            return useValidator({
                target: this.firstName,
                messages: this.messages,
                rules: [
                    { type: "name", name: "firstName", message: "First name required" }
                ]
            })
        },

        lastNameValidator() {
            return useValidator({
                target: this.lastName,
                messages: this.messages,
                rules: [
                    { type: "name", name: "lastName", message: "Last name required" }
                ]
            })
        },

        emailValidator() {
            return useValidator({
                target: this.email,
                messages: this.messages,
                rules: [
                    { type: "email", name: "email", message: "Enter valid email" }
                ]
            })
        },

        passwordValidator() {

            return useValidator({
                target: this.password,
                messages: this.messages,
                rules: [
                    { type: "required", name: "password", message: "Password required" },
                    { type: "password", name: "password", message: "Minimum eight characters, one letter, one number" }
                ]
            });
        },

        passwordConfirmValidator() {
            const samePassword = (target) => {
                return target === this.password
            };

            return useValidator({
                target: this.passwordConfirm,
                messages: this.messages,
                rules: [
                    { type: "required", name: "passwordConfirm", message: "Password confirm required" },
                    {
                        type: "callback",
                        alias: "passwordConfirmSame",
                        name: "passwordConfirm",
                        message: "Password must be same",
                        callback: samePassword
                    }
                ]
            });
        }
    },

    methods: {
        checkForm() {
            this.validateFields();
            this.handleServerError();

            if (isValidMessages(this.messages)) {
                this.register({
                    firstName: this.firstName,
                    lastName: this.lastName,
                    password: this.password,
                    email: this.email
                })
                    .then(() => this.changeModal('closed'))
                    .catch(result => {
                        const errors = result.response.data.errors;

                        addServerMessageErrors(this.messages, errors);
                    });
            }
        },

        handleServerError() {
            deleteServerMessageErrors(this.messages);
        },

        validateFields() {
            this.firstNameValidator.validate();
            this.lastNameValidator.validate();
            this.emailValidator.validate();
            this.passwordValidator.validate();
            this.passwordConfirmValidator.validate();
        },

        redirectLogin() {
            this.changeModal('login');
        },

        ...mapMutations({
            changeModal: "changeModal",
            authorize: "authorize"
        }),
        ...mapActions(['register'])
    },
    components: {
        AppModal,
        AppField
    }
}
</script>
