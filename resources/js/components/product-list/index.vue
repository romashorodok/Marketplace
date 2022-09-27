<template>
    <div v-if="products">
        <ul class="product-wrapper">
            <li class="product-card" v-for="product in products">
                <app-product :product="product"/>
            </li>
        </ul>
        <app-pagination :current-page="currentPage" :pages="pages" @onChange="changePage"/>
    </div>
    <div class="empty-section product-wrapper" v-else>
        <img src="/icons/cube.svg" width="40" alt=""/>
        <p>Nothing found</p>
    </div>
</template>

<script>
import {useStore, mapGetters} from "vuex";
import AppProduct from "./product";
import AppPagination from '@/shared/pagination';

export default {
    setup: async () => {
        const store = useStore();

        await store.dispatch('fetchProductsByFilters');

        return {store};
    },

    methods: {
        changePage(page) {
            this.store.dispatch('changePage', page)
                .then(() => window.scrollTo({
                    top: 0,
                    left: 0,
                    behavior: 'smooth'
                }))
                .catch(console.error);
        }
    },

    computed: {
        ...mapGetters({
            products: "getProducts",
            pages: "getPages",
            currentPage: "getCurrentPage"
        }),
    },

    components: {AppProduct, AppPagination}
}
</script>
