<template>
    <app-modal>
        <template v-slot:modal-header>
            <h3 class="modal-header-name">
                Sign Up
            </h3>
        </template>
        <template v-slot:modal-body>
            <form @submit="checkForm">
                <div class="body-field">
                    <label>First name</label>
                    <input v-model="firstName" type="text">
                    <p v-show="firstNameMessage" class="error-message">
                        {{ firstNameMessage }}
                    </p>
                </div>
                <div class="body-field">
                    <label>Last name</label>
                    <input v-model="lastName" type="text">
                    <p v-show="lastNameMessage" class="error-message">
                        {{ lastNameMessage }}
                    </p>
                </div>
                <div class="body-field">
                    <label>Email</label>
                    <input v-model="email" type="email">
                    <p v-show="emailMessage" class="error-message">
                        {{ emailMessage }}
                    </p>
                </div>
                <div class="body-field">
                    <label>Password</label>
                    <input v-model="password" type="password">
                    <p v-show="passwordMessage" class="error-message">
                        {{ passwordMessage }}
                    </p>
                </div>
                <div class="body-field">
                    <label>Password confirmation</label>
                    <input v-model="passwordConfirm" type="password">
                    <p v-show="passwordConfirmMessage" class="error-message">
                        {{ passwordConfirmMessage }}
                    </p>
                </div>
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
import { mapActions, mapMutations } from "vuex";
import AppModal from '@/shared/modal';

export default {
    data: () => ({
        firstName: null,
        lastName: null,
        password: null,
        passwordConfirm: null,
        email: null,

        firstNameMessage: null,
        lastNameMessage: null,
        passwordMessage: null,
        passwordConfirmMessage: null,
        emailMessage: null
    }),
    methods: {
        checkForm(event) {
            const nameRegex = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
            const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if (!nameRegex.test(this.firstName))
                this.firstNameMessage = "Enter valid fist name";
            else
                this.firstNameMessage = null;

            if (!nameRegex.test(this.lastName))
                this.lastNameMessage = "Enter valid last name";
            else
                this.lastNameMessage = null;

            if (!emailRegex.test(this.email))
                this.emailMessage = "Enter valid email";
            else
                this.emailMessage = null;

            if (!passwordRegex.test(this.password))
                this.passwordMessage = "Minimum eight characters, one letter, one number";
            else
                this.passwordMessage = null;

            if (this.password !== this.passwordConfirm)
                this.passwordConfirmMessage = "Password must be same";
            else
                this.passwordConfirmMessage = null;

            if (!this.firstNameMessage
                && !this.lastNameMessage
                && !this.emailMessage
                && !this.passwordMessage
                && !this.passwordConfirmMessage
            ) {
                axios.post('/api/register', {
                    firstName: this.firstName,
                    lastName: this.lastName,
                    password: this.password,
                    email: this.email,
                })
                    .then(result => {
                        if(result.data.token)
                            this.authorize(result.data.token);
                        else
                            console.log("Success register, notify user");

                        this.changeModal('closed');
                    })
                    .catch(error => {
                        switch (error.response.data.message) {
                            case "The email has already been taken.": {
                                this.emailMessage = "The email has already been taken.";
                            }
                        }

                        console.log(error);
                    });
            }

            event.preventDefault();
        },

        redirectLogin() {
            this.changeModal('login');
        },

        ...mapMutations({
            changeModal: "changeModal",
            authorize: "authorize"
        })
    },
    components: { AppModal }
}
</script>
