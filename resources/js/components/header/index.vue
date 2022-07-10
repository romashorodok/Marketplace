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
        <login :show="loginVisible" @close="loginToggle"/>
    </header>
</template>

<script>
import { toRefs } from "vue";
import { logout, state as userState } from '../../user';
import login from '../login';

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
        login
    },

    methods: {
        loginToggle() {
            this.loginVisible = !this.loginVisible;
        }
    }
}
</script>
