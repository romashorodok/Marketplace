
export default {
    state: () => ({
        page: null,
        pagination: []
    }),
    mutations: {
        setProducts(state, { page, products }) {
            state.pagination[page] = products;
            state.page = page;
        }
    },

    actions: {
        fetchProducts(context) {
            return axios.get('/api/product')
                .then(req => req.data.products)
                .then(products => {

                    context.commit('setProducts', {
                        page: products.current_page,
                        products: products.data
                    });
                });
        }
    },

    getters: {
        getProducts: (state) => state.pagination[state.page],
    }
}
