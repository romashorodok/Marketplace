<template>
    <modal v-show="show" @close="">
        <template v-slot:modal-header>
            <h3>Login</h3>
        </template>
        <template v-slot:modal-body>
            <div class="wrapper-field">
                <label>Email</label>
                <input v-model="credentials.email" type="email">
            </div>
            <div class="wrapper-field">
                <label>Password</label>
                <input v-model="credentials.password" type="password">

                <button @click="login(credentials)">
                    Login
                </button>
            </div>
        </template>
        <template v-slot:modal-footer>
        </template>
    </modal>
</template>

<script>
import { login } from '../../user';
import modal from '../modal';

export default {
    props: ['show'],

    setup() {
        return {
            login
        }
    },

    data: () => {
        return {
            credentials: {
                email: '',
                password: ''
            }
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
