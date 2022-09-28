
export default  {
    state: () => ({
        categories: [],
        name: ''
    }),

    mutations: {
        addCategoryQuery(state, { name }) {
            state.categories.push(name);
        },

        removeCategoryQuery(state, { name }) {
            state.categories = state.categories.filter(val => val !== name)
        },

        setSearchName(state, { name }) {
            state.name = name;
        }
    },

    actions: {
        getQueryURL(context) {
            return new URLSearchParams({
                categories: context.getters.getFilterCategories,
                name: context.getters.getSearchName
            });
        },

        addOrRemoveCategory(context, { name, isAdd }) {
            const category = { name, active: isAdd }

            if (isAdd)
                context.commit('addCategoryQuery', category);
            else
                context.commit('removeCategoryQuery', category);
        },

        mutateSearchName(context, { name }) {
            context.commit('setSearchName', {name});
        }
    },

    getters: {
        getFilterCategories: (state) => state.categories,
        getSearchName: (state) => state.name
    }
}
