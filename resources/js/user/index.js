import { reactive } from 'vue';

/**
 * https://vuejs.org/api/reactivity-core.html
 */
export const state = reactive({
    isAuthorized: false,

    user: null
});

export const authorize = (user) => {
    if (user) {
        state.user = user;
        state.isAuthorized = true;

        return true;
    } else {
        state.user = null;
        state.isAuthorized = false;

        return false;
    }
};

export const login = (credentials) => {

    return axios.post('/api/login', {...credentials})
        .then(result => authorize(result.data.user))
        .catch(error => false)
};

export const logout = () => {
    authorize(null);
}
