import axios from "axios";

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
        page: 1,
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
        },

        setEmptyProducts(state) {
            state.page = 1;
            state.pagination = [];
            state.pages = [];
        },

        setPage(state, page) {
            state.page = page;
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
                page: context.getters.getCurrentPage,
                ...Object.fromEntries(filterQuery),
                ...Object.fromEntries(pageSizeQuery),
            });

            const products = await axios.get('/api/product', {params: query})
                .then(req => req.data.products)
                .catch(req => {
                    console.error(req);
                    return null;
                });

            if (!products) {
                await context.commit('setEmptyProducts');
                return;
            }

            mapProductToState(context, products);

            await context.commit('setPages', products.pages);

            return products;
        },

        async getProductQuery(context) {
            const query = new URLSearchParams({
                page: context.getters.getCurrentPage,
                ...Object.fromEntries(await context.dispatch('getQueryURL'))
            });

            [...query.entries()].forEach(([key, value]) => {
                if (!value) {
                    query.delete(key);
                }
            });

            return query;
        },

        async restoreProductQuery(context, {
            page,
            categories,
            name
        }) {
            const pageInt = parseInt(page);
            const categoriesArr = categories ? categories.split(',') : '';

            if (pageInt) await context.dispatch('changePage', pageInt);
            if (categoriesArr) await categoriesArr.forEach(category => context.dispatch('addOrRemoveCategory', {
                name: category,
                isAdd: true
            }));
            if (name) await context.dispatch('mutateSearchName', {name});
        },

        changePage(context, page) {
            if (context.getters.getPages.length !== 0) {
                const nextPage = context.getters.getPages.find(item => item.number === page);
                const pageSizeQuery = getPaginationSizeQuery();

                if (nextPage.link !== null)
                    return axios.get(nextPage.link, {params: pageSizeQuery})
                        .then(req => req.data.products)
                        .then(async products => {
                            mapProductToState(context, products);
                            await context.commit('setPages', products.pages);
                        });

                return Promise.reject('Unreachable page');
            } else {
                context.commit('setPage', page);
            }
        }

    },

    getters: {
        getProducts: (state) => state.pagination ? state.pagination[state.page] : null,
        getPages: (state) => state.pages ? state.pages : null,
        getCurrentPage: (state) => state.page ? state.page : null
    }
}
