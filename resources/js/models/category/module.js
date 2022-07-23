
export default {
    state: () => ({
        categories: []
    }),

    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
        }
    },

    actions: {
        fetchCategories(context) {
            return axios.get('/api/category')
                .then(req => req.data.categories)
                .then(categories => {
                    context.commit('setCategories', categories)
                });
        }
    },

    getters: {
        getCategories: (state) => state.categories
    }
};
