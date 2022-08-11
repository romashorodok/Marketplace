const mapProductToState = (context, product) => {
    context.commit('setProducts', {
        page: product.currentPage,
        products: product.data
    });
};

const MOBILE = 18;
const TABLET = 30;
const DESKTOP = 50;

const getPageSize = () => {
    const width = window.innerWidth || document.documentElement.clientWidth ||
        document.body.clientWidth;

    if (width <= 667)
        return MOBILE;
    else if (width <= 1024)
        return TABLET;
    else
        return DESKTOP;
};

const getPaginationSizeQuery = () => {
    return new URLSearchParams({
        size: getPageSize()
    });
}

export default {
    state: () => ({
        page: null,
        pagination: [],
        pages: []
    }),
    mutations: {
        setProducts(state, {page, products}) {
            state.pagination[page] = products;
            state.page = page;
        },

        setPages(state, payload) {
            state.pages = payload;
        }
    },

    actions: {
        fetchProducts(context) {
            const pageSizeQuery = getPaginationSizeQuery();

            return axios.get('/api/product', {
                params: pageSizeQuery
            })
                .then(req => req.data.products)
                .then(products => {
                    mapProductToState(context, products);

                    context.commit('setPages', products.pages);
                });
        },

        async fetchProductsByFilters(context) {
            const filterQuery = await context.dispatch('getQueryURL');
            const pageSizeQuery = getPaginationSizeQuery();

            const query = new URLSearchParams({
                ...Object.fromEntries(filterQuery),
                ...Object.fromEntries(pageSizeQuery)
            });

            const products = await axios.get('/api/product', {params: query})
                .then(req => req.data.products);

            mapProductToState(context, products);

            await context.commit('setPages', products.pages);

            return products;
        },

        changePage(context, page) {
            const nextPage = context.getters.getPages.find(item => item.number === page);
            const pageSizeQuery = getPaginationSizeQuery();

            if (nextPage.link !== null)
                return axios.get(nextPage.link, {params: pageSizeQuery})
                    .then(req => req.data.products)
                    .then(products => {
                        mapProductToState(context, products);
                        context.commit('setPages', products.pages);
                    });

            return Promise.reject('Unreachable page');
        }

    },

    getters: {
        getProducts: (state) => state.pagination[state.page],
        getPages: (state) => state.pages,
        getCurrentPage: (state) => state.page
    }
}
