
export default  {
    state: () => ({
        categories: []
    }),

    mutations: {
        addCategoryQuery(state, { name }) {
            state.categories.push(name);
        },
        removeCategoryQuery(state, { name }) {
            state.categories = state.categories.filter(val => val !== name)
        },
    },

    actions: {
        getQueryURL(context) {
            return new URLSearchParams({
                categories: context.getters.getFilterCategories
            });
        },

        addOrRemoveCategory(context, { name, isAdd }) {
            if (isAdd)
                context.commit('addCategoryQuery', { name });
            else
                context.commit('removeCategoryQuery', { name });
        }
    },

    getters: {
        getFilterCategories: (state) => state.categories
    }
}
