import product from "@components/product-list/product";

const mapProductToState = (context, product) => {
    context.commit('setProducts', {
        page: product.current_page,
        products: product.data
    });
};

export default {
    state: () => ({
        page: null,
        pagination: [],
        pages: []
    }),
    mutations: {
        setProducts(state, { page, products }) {
            state.pagination[page] = products;
            state.page = page;
        },

        setPages(state, payload) {
            state.pages = payload.slice(1, -1);
        }
    },

    actions: {
        fetchProducts(context) {
            return axios.get('/api/product')
                .then(req => req.data.products)
                .then(products => {

                    mapProductToState(context, products);

                    context.commit('setPages', products.links);
                });
        },

        async fetchProductsByFilters(context) {
            const filterQuery = await context.dispatch('getQueryURL');

            if (filterQuery.get('filter') !== '') {
                const products = await axios.get('/api/product', {
                    params: filterQuery
                }).then(req => req.data.products);

                console.log(products)

                await context.commit('setPages', products.links);

                mapProductToState(context, products);

                return products;
            }
            else
                console.log('empty filters');
        },

        changePage(context, page) {
            const nextPage = context.getters.getPages[page];

            if (nextPage.label !== "...")
                axios.get(nextPage.url)
                    .then(req => req.data.products)
                    .then(page => {
                        mapProductToState(context, page);
                        context.commit('setPages', page.links);
                    })
        }

    },

    getters: {
        getProducts: (state) => state.pagination[state.page],
        getPages: (state) => state.pages,
        getCurrentPage: (state) => state.page
    }
}
