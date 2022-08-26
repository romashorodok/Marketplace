<template>
    <form @submit.prevent="checkForm">
        <section class="account-profile-section">
            <h4 class="profile-label">Avatar</h4>
            <ul class="list-group">
                <li class="list-item">
                    <img v-if="image?.path"
                         :src="image.path"
                         referrerpolicy="no-referrer"
                         alt="image"
                    class="profile-avatar"/>
                    <span v-else class="profile-avatar-span"/>
                </li>
            </ul>
        </section>
        <section class="account-profile-section">
            <h4 class="profile-label">Profile</h4>
            <ul class="list-group">
                <li class="list-item">
                    <app-field label="First name"
                               v-model:model="firstName"
                               v-model:messages="messages.firstName"
                               v-on:validate="firstNameValidator.validate" />
                </li>
                <li class="list-item">
                    <app-field label="Last name"
                               v-model:model="lastName"
                               v-model:messages="messages.lastName"
                               v-on:validate="lastNameValidator.validate" />
                </li>
            </ul>
        </section>
        <section class="account-profile-section">
            <h4 class="profile-label">Credentials</h4>
            <ul class="list-group">
                <li class="list-item">
                    <app-field label="Email"
                               v-model:model="email"
                               v-model:messages="messages.email"
                               :type="'email'"
                               :disabled="true"
                               v-on:validate="emailValidator.validate" />
                </li>

                <li class="list-item">
                    <app-field label="Current password"
                               v-model:model="password"
                               v-model:messages="messages.password"
                               :type="'password'"
                               v-on:validate="passwordValidator.validate" />
                </li>

                <li class="list-item">
                    <app-field label="New password"
                               v-model:model="passwordNew"
                               v-model:messages="messages.passwordNew"
                               :type="'password'"
                               v-on:validate="passwordNewValidator.validate" />
                </li>
                <li class="list-item">
                    <app-field label="New password confirm"
                               v-model:model="passwordNewConfirm"
                               v-model:messages="messages.passwordNewConfirm"
                               :type="'password'"
                               v-on:validate="passwordNewConfirmValidator.validate" />
                </li>
            </ul>
        </section>
        <button class="profile-save-btn" type="submit">
            Update
        </button>
    </form>
</template>

<script>
import {useStore, mapGetters } from "vuex";
import ProfileField from './components/profile-field';
import useValidator from "@/shared/hooks/useValidator";
import {
    isValidMessages,
    addServerMessageErrors,
    deleteServerMessageErrors,
} from "@/shared/hooks/useValidator";
import AppField from '@/shared/field';

export default {
    setup: async () => {
        const store = useStore();

        await store.dispatch('fetchUser');

        return {
            store
        };
    },

    data: () => ({
        firstName: null,
        lastName: null,
        password: null,
        passwordNew: null,
        passwordNewConfirm: null,
        email: null,
        image: null,

        messages: { },
    }),

    mounted() {
        Object.assign(this, {...this.store.getters.getUser});
    },

    methods: {
        checkForm() {
            this.validateFields();
            this.handleServerError();

            if (isValidMessages(this.messages)) {

                const userForm = {
                    firstName: this.firstName,
                    lastName: this.lastName,
                    email: this.email,
                };

                const passwordCondition = this.password && this.passwordNew && this.passwordNewConfirm && this.passwordNew === this.passwordNewConfirm;

                const user = passwordCondition
                    ? {
                        ...userForm,
                        password: this.password,
                        passwordNew: this.passwordNew,
                        passwordNewConfirm: this.passwordNewConfirm
                    }
                    : userForm;

                this.store.dispatch('updateUser', user)
                    .then(this.resetPasswordFields)
                    .catch(result => {
                        const errors = result.response.data.errors;

                        addServerMessageErrors(this.messages, errors);
                    });
            }
        },

        validateFields() {
            this.firstNameValidator.validate();
            this.lastNameValidator.validate();
            this.emailValidator.validate();
            this.passwordValidator.validate();
            this.passwordNewValidator.validate();
            this.passwordNewConfirmValidator.validate();
        },

        handleServerError() {
            deleteServerMessageErrors(this.messages);
        },

        resetPasswordFields() {
            this.password = this.passwordNew = this.passwordNewConfirm = null;
        }
    },

    computed:  {
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
                nullable: true,
                rules: [
                    { type: "password", name: "password", message: "Minimum eight characters, one letter, one number" }
                ]
            });
        },

        passwordNewValidator() {
            return useValidator({
                target: this.passwordNew,
                messages: this.messages,
                nullable: true,
                rules: [
                    { type: "password", name: "passwordNew", message: "Minimum eight characters, one letter, one number" }
                ]
            });
        },

        passwordNewConfirmValidator() {
            const samePassword = (target) => {
                return target === this.passwordNew
            };

            return useValidator({
                target: this.passwordNewConfirm,
                messages: this.messages,
                rules: [
                    {
                        type: "callback",
                        alias: "passwordConfirmSame",
                        name: "passwordNewConfirm",
                        message: "New password must be same",
                        callback: samePassword
                    }
                ]
            });
        },

        ...mapGetters(['getUser'])
    },

    components: { ProfileField, AppField }
}
</script>
