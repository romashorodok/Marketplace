<template>
    <ul class="product-wrapper">
        <li class="product-card" v-for="product in products">
            <div class="card-wrapper">
                <img v-if="product.image?.image_path"
                     v-bind:src="product.image?.image_path"
                     class="card-image"
                     alt="">
                <a v-else class="card-image--grey"/>

                <a class="app-btn-link card-name"> {{ product.name }} </a>
                <p class="card-price"> {{ product.price }}</p>
                <p v-show="product.category?.name">
                    Category: {{ product.category?.name }}
                </p>
            </div>
        </li>
    </ul>

    <div class="pagination-wrapper">
       <a v-for="(page, index) in pages"
          class="app-btn"
          @click="changePage(index)"
          v-bind:class="[page.label === currentPage.toString() ? 'app-btn-active' : '' ]"
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
