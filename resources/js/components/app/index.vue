<template>
    <header class="app-header">
        <router-link class="app-btn" to="/">Home</router-link>

        <div class="header-action" v-if="token">
            <a class="app-btn" @click="logout">Log out</a>
        </div>
        <div class="header-action" v-else>
            <a class="app-btn">Sign Up</a>
            <a class="app-btn" @click="openModal">Login</a>
        </div>
    </header>
    <main class="app-main">
        <router-view />

        <div v-show="modal === 'register'">
            <app-login @close="closeModal"></app-login>
        </div>
    </main>
</template>

<script>
import AppLogin from '@components/login';
import {mapState, mapMutations, useStore, mapActions} from "vuex";

export default {
    setup: () => ({ store: useStore() }),
    computed: mapState({
        modal: state => state.app.modal,
        token: state => state.user.token
    }),
    methods: {
        openModal() {
            this.store.commit({
               type: "changeModal",
               modal: 'register',
            });
        },

        closeModal() {
            this.store.commit({
                type: "changeModal",
                modal: 'closed',
            });
        },

        ...mapMutations({
            changeModal: "changeModal",
        }),
        ...mapActions(['logout'])
    },
    components: { AppLogin }
}
</script>
