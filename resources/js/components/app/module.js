
export const modal = {
    register: 'register',
    closed: 'closed'
};

export default {
    state: () => ({
        modal: modal.closed,
    }),
    mutations: {
        changeModal(state, payload) {
            state.modal = payload.modal;
        }
    },
    actions: { },
    getters: { }
};
