<template>
    <form @submit="checkForm">
        <section class="account-profile-section">
            <h4 class="profile-label">Avatar</h4>
            <ul class="list-group">
                <li class="list-item">
                    <span class="profile-avatar-span"/>
                </li>
            </ul>
        </section>
        <section class="account-profile-section">
            <h4 class="profile-label">Profile</h4>
            <ul class="list-group">
                <profile-field label="First name"
                               :model="user.firstName"
                               :message="messages.firstName"
                               @on-change="validateFirstName" />
                <profile-field label="Last name"
                               :model="user.lastName"
                               :message="messages.lastName"
                               @on-change="validateLastName" />
            </ul>
        </section>
        <section class="account-profile-section">
            <h4 class="profile-label">Credentials</h4>
            <ul class="list-group">
                <profile-field label="Email"
                               :model="user.email"
                               :message="messages.email"
                               :disabled="true" />
                <profile-field label="Current password"
                               :model="password"
                               :type="'password'"
                               :message="messages.password"
                               @on-change="validatePassword"/>
                <profile-field label="New password"
                               :model="newPassword"
                               :type="'password'"
                               :message="messages.newPassword"
                               @on-change="validateNewPassword"/>
                <profile-field label="Confirm new password"
                               :model="confirmNewPassword"
                               :type="'password'"
                               :message="messages.confirmNewPassword"
                               @on-change="validateConfirmNewPassword"/>
            </ul>
        </section>
        <button class="profile-save-btn" type="submit">
            Update
        </button>
    </form>
</template>

<script>
import { computed } from 'vue';
import {useStore, mapGetters } from "vuex";

import ProfileField from './components/profile-field';

export default {
    setup: async () => {
        const store = useStore();

        await store.dispatch('fetchUser');

        return {
            user: computed(() => Object
                .assign({}, store.getters.getUser)),
            store
        };
    },
    data: () => ({
        messages: [],
        password: null,
        newPassword: null,
        confirmNewPassword: null
    }),
    methods: {
        checkForm(event) {
            event.preventDefault();

            if (!this.isSame(this.user, this.getUser)
                || this.password
                || this.newPassword
                || this.confirmNewPassword
            ) {
                if (!this.messages.firstName
                    && !this.messages.lastName
                    && !this.messages.email
                    && !this.messages.password
                    && !this.messages.newPassword
                    && !this.messages.confirmNewPassword
                ) {
                    const user = this.password && this.newPassword
                        ? { ...this.user,
                            password: this.password,
                            newPassword: this.newPassword }
                        : this.user;

                    this.store.dispatch('updateUser', user)
                        .then(this.resetPasswordFields)
                        .catch(error => {
                            switch (error.response.data.message) {
                                case "The password incorrect.": {
                                    this.messages.password = "The password incorrect.";
                                    break;
                                }
                            }
                        });
                }
            }
        },

        validateFirstName(firstName) {
            this.user.firstName = firstName;

            if (!this.validateNameRegex(firstName))
                this.messages.firstName = "Enter valid first name";
            else
                this.messages.firstName = null;
        },

        validateLastName(lastName) {
            this.user.lastName = lastName;

            if (!this.validateNameRegex(lastName))
                this.messages.lastName = "Enter valid last name";
            else
                this.messages.lastName = null;
        },

        validatePassword(password) {
            this.password = password;

            if (!this.validatePasswordRegex(password))
                this.messages.password = "Minimum eight characters, one letter, one number";
            else
                this.messages.password = null;
        },

        validateNewPassword(password) {
            this.newPassword = password;

            if (!this.validatePasswordRegex(password))
                this.messages.newPassword = "Minimum eight characters, one letter, one number";
            else
                this.messages.newPassword = null;

            this.validateConfirmNewPassword(this.confirmNewPassword);
        },

        validateConfirmNewPassword(password) {
            this.confirmNewPassword = password;

            if (!(this.confirmNewPassword === this.newPassword)) {
                this.messages.confirmNewPassword = "Password must be same";
            }
            else if (this.confirmNewPassword === this.password) {
                this.messages.confirmNewPassword = "The new password must not match the current";
            }
            else
                this.messages.confirmNewPassword = null;
        },

        validatePasswordRegex(password) {
            const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;;
            return regex.test(password);
        },

        validateNameRegex(name) {
            const regex = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/;
            return regex.test(name);
        },

        isSame(source, target) {
            return JSON.stringify(source) === JSON.stringify(target)
        },

        resetPasswordFields() {
            this.password = this.confirmNewPassword = this.newPassword = null;
        }

    },
    computed:  {
        ...mapGetters(['getUser'])
    },
    components: { ProfileField }
}
</script>
