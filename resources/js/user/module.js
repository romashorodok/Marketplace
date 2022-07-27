import getAltAxis from "@popperjs/core/lib/utils/getAltAxis";

const tokenKey = 'token';

export default {
    state: () => ({
        token: null,
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
            const token = localStorage.getItem(tokenKey);

            try {
                //TODO: add axios interceptor for bearer token.
                await axios.get('/api/logout', {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                localStorage.removeItem(tokenKey);
                context.commit('unauthorize');
            } catch (e) {
                localStorage.removeItem(tokenKey);
                context.commit('unauthorize');
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

        async restoreToken(context) {
            const token = localStorage.getItem(tokenKey);

            if (token)
                try {
                    await axios.get('/api/token', {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });

                    context.commit('authorize', token);
                } catch (e) {
                    await context.dispatch('logout');
                }
        }
    },
    getters: {}
};
