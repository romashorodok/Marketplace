import router from '@/router';
import {useCart} from "@/composables/useCart";

const tokenKey = 'token';

const config = (token) => ({
    headers: {
        Authorization: `Bearer ${token}`
    }
});

export default {
    state: () => ({
        token: null,
        user: null
    }),
    mutations: {
        authorize(state, payload) {
            if (payload) {
                state.token = payload;
                localStorage.setItem(tokenKey, payload);
            }
        },

        unauthorize(state) {
            state.token = null;
            localStorage.removeItem(tokenKey);
        },

        setUser(state, payload) {
            state.user = payload.account;
        }
    },
    actions: {
        async login(context, credential) {
            const {token} = await axios.post('/api/login', credential).then(resp => resp.data);

            if (token) {
                await context.commit('authorize', token);
                context.commit('changeModal', 'closed');
                localStorage.setItem('token', token);
            }

            await useCart().fetchCart();
        },

        async logout(context) {
            const token = context.getters.getToken;

            try {
                await axios.get('/api/logout', config(token));

                context.commit('unauthorize');
                await router.push('/');
            } catch (e) {
                context.commit('unauthorize');
                await router.push('/');
            }
        },

        register(context, credentials) {
            return axios.post('/api/register', credentials)
                .then(resp => resp.data.token)
                .then(token => {
                    if (token)
                        //Login user
                        context.commit('authorize', token);
                    else
                        //When registration complete, but server not send token
                        console.log("Success register, notify user");
                });
        },

        async fetchUser(context) {
            const token = localStorage.getItem(tokenKey);

            const user = await axios.get('/api/account', config(token));

            context.commit('setUser', user.data);

            return context.getters.getUser;
        },

        updateUser(context, user) {
            const token = context.getters.getToken;

            return axios.post('/api/account', user, config(token));
        },

        async restoreToken(context) {
            try {
                const {token} = await axios.get('/api/token',
                    config(
                        localStorage.getItem('token')
                    )).then(res => res.data);

                await context.commit('authorize', token);
            } catch (e) {
                context.commit('unauthorize');
                console.error(e);
            }
        }
    },
    getters: {
        getUser: (state) => state.user,
        getToken: (state) => state.token,
    }
};
