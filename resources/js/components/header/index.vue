<template>
    <header class="header">
        <div class="header-home">
            <router-link to="/">Home</router-link>
        </div>
        <div class="header-account">
            <button @click="loginToggle">Login</button>
        </div>

        <login :show="loginVisible" @close="loginToggle"/>
    </header>
</template>

<script>
import login from '../login';

export default {
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
