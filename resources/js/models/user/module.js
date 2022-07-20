import router from '@/router';

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
            const response = await axios.post('/api/login', credential);

            if (response.data.token) {
                context.commit('authorize', response.data.token);
                context.commit('changeModal', 'closed');
            }
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
            const token = localStorage.getItem(tokenKey);

            if (token)
                try {
                    await axios.get('/api/token', config(token));

                    context.commit('authorize', token);
                } catch (e) {
                    await context.dispatch('logout');
                }
        }
    },
    getters: {
        getUser: (state) => state.user,
        getToken: (state) => state.token,
    }
};
