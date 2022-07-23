<template>
    <ul class="product-wrapper">
        <li class="product-card" v-for="product in products">
            <div class="card-wrapper">
                <a class="card-image" />
                <a class="app-btn-link card-name"> {{ product.name }} </a>
                <p class="card-price"> {{ product.price }}</p>
            </div>
        </li>
    </ul>

    <div class="pagination-wrapper">
       <a v-for="(page, index) in pages"
          class="app-btn"
          @click="changePage(index)"
          v-bind:class="[index === (currentPage) - 1 ? 'app-btn-active' : '' ]"
       >
           {{ page.label }}
       </a>
    </div>

</template>

<script>
import {useStore, mapGetters} from "vuex";

export default {
    setup: async () => {
        const store = useStore();

        await store.dispatch('fetchProducts');

        return { store };
    },

    mounted() {
      console.log(this.products)
    },

    methods: {
        changePage(page) {
           this.store.dispatch('changePage', page);
        }
    },

    computed: {
        ...mapGetters({
            products: "getProducts",
            pages: "getPages",
            currentPage: "getCurrentPage"
        }),
    }
}
</script>
