<template>
    <div class="app-header">
        <router-link class="app-btn" to="/">Home</router-link>

        <div class="header-action" v-if="token">
            <router-link class="app-btn" to="/account/profile">
                My account
            </router-link>
            <a class="app-btn" @click="logout">Log out</a>
        </div>
        <div class="header-action" v-else>
            <a class="app-btn"
               @click="openModal('register')">
                Sign Up
            </a>
            <a class="app-btn"
               @click="openModal('login')">
                Login
            </a>
        </div>
    </div>
    <main class="app-main">
        <router-view />

        <app-login v-show="modal === 'login'" @close="closeModal"/>
        <app-register v-show="modal === 'register'" @close="closeModal" />
    </main>
</template>

<script>
import AppLogin from '@components/login';
import AppRegister from '@components/register';
import {mapState, mapMutations, mapActions} from "vuex";

export default {
    computed: mapState({
        modal: state => state.app.modal,
        token: state => state.user.token
    }),
    methods: {
        openModal(modalWindow) {
            this.changeModal(modalWindow);
        },

        closeModal() {
            this.changeModal('closed');
        },

        ...mapMutations({
            changeModal: "changeModal",
        }),
        ...mapActions(['logout'])
    },

    components: { AppLogin, AppRegister }
}
</script>
