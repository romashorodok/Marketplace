<template>
    <header class="header">
        <router-link to="/">Home</router-link>
        <div class="header-account">
            <div v-if="isAuthorized">
                <h3>{{ user.name }}</h3>

                <button @click="logout">Logout</button>
            </div>
            <div v-else>
                <button @click="">Sign Up</button>
                <button @click="loginToggle">Login</button>
            </div>
        </div>

        <app-login :show="loginVisible" @close="loginToggle"/>
    </header>
</template>

<script>
import { toRefs } from "vue";
import { logout, state as userState } from '@user';
import AppLogin from '@components/login';

export default {

    setup() {
        const { isAuthorized, user } = toRefs(userState);

        return {
            isAuthorized,
            user,

            logout
        };
    },

    data() {
        return {
            loginVisible: false
        }
    },

    components: {
        AppLogin
    },

    methods: {
        loginToggle() {
            this.loginVisible = !this.loginVisible;
        }
    }
}
</script>
