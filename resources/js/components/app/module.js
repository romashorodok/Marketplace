
export const modal = {
    register: 'register',
    login: 'login',
    closed: 'closed'
};

export default {
    state: () => ({
        modal: modal.closed,
    }),
    mutations: {
        changeModal(state, payload) {
            state.modal = payload;
        }
    },
    actions: { },
    getters: { }
};
