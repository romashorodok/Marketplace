<template>
    <ul class="product-wrapper">
        <li class="product-card" v-for="product in products">
            <app-product :product="product"/>
        </li>
    </ul>
    <div class="pagination-wrapper">
        <a v-for="page in pages"
           class="app-btn"
           :class="[page.number === currentPage ? 'app-btn-active' : '']"
           @click="changePage(page.number)">
            {{ page.number }}
        </a>
    </div>
</template>

<script>
import {useStore, mapGetters} from "vuex";
import AppProduct from "./product";

export default {
    setup: async () => {
        const store = useStore();

        await store.dispatch('fetchProducts');

        return {store};
    },

    methods: {
        changePage(page) {
            if (page !== this.currentPage)
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

    components: {AppProduct}
}
</script>
