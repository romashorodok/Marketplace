<template>
    <modal>
        <template v-slot:modal-header>
            <h3 class="modal-header-name">Login</h3>
        </template>

        <template v-slot:modal-body>
            <section class="modal-login-auth">
                <div class="body-field">
                    <label>Email</label>
                    <input v-model="credentials.email" type="email">
                    <p v-show="emailMessage" class="error-message">
                        {{ emailMessage }}
                    </p>
                </div>
                <div class="body-field">
                    <label>Password</label>
                    <input v-model="credentials.password" type="password">
                    <p v-show="passwordMessage" class="error-message">
                        {{ passwordMessage }}
                    </p>
                </div>

                <button
                    class="login-confirm app-btn"
                    @click="checkForm">
                    Login
                </button>
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

        <template v-slot:modal-footer>

        </template>
    </modal>
</template>

<script>
import modal from '@/shared/modal';
import {useStore, mapActions} from "vuex";

export default {
    setup: () => ({store: useStore()}),
    methods: {
        checkForm() {
            const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if (!emailRegex.test(this.credentials.email)) {
                this.emailMessage = "Enter valid email";
            } else {
                this.emailMessage = null;
            }

            if (!this.credentials.password){
                this.passwordMessage = "Enter password";
            } else  {
                this.passwordMessage = null;
            }

            if (!this.emailMessage && !this.passwordMessage) {
                this.login(this.credentials)
                    .then()
                    .catch(error => {
                        this.passwordMessage = error.response.data.message
                    });
            }
        },

        redirectRegister() {
            console.log(this.credentials);
        },

        ...mapActions(['login'])
    },
    data: () => {
        return {
            credentials: {
                email: null,
                password: null
            },

            emailMessage: null,
            passwordMessage: null
        }
    },
    components: {
        modal
    }
}
</script>

<style scoped>
input {
    margin: 5px;
}

.wrapper-field {
    display: flex;
    flex-direction: column;
}

h3 {
    margin: 0;
}
</style>
