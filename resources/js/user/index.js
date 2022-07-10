import {reactive, toRefs} from 'vue';

/**
 * https://vuejs.org/api/reactivity-core.html
 */
const state = reactive({
    isAuthorized: false,

    user: null
});

const authorize = (user) => {
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

const login = (credentials) => {
    axios.post('/api/login', {...credentials})
        .then(result => authorize(result.data.user))
        .catch(error => console.log(error))
};

const logout = () => {
    authorize(null);
}

export { login, logout, state };
