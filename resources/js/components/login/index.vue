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
import modal from '@components/modal';

import { login } from '@user';

export default {
    props: {
        show: Boolean
    },

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

    methods: {
        async login() {
            const result = await login(this.credentials);

            if (result)
                this.$emit('close');
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
