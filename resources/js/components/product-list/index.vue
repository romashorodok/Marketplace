<template>
    <ul class="product-wrapper">
        <li class="product-card" v-for="product in products">
            <div class="card-wrapper">
                <a class="card-image" />
                <a class="card-name"> {{ product.name }} </a>
                <p class="card-price"> {{ product.price }}</p>
            </div>
        </li>
    </ul>
</template>

<script>
import { useStore, mapGetters } from "vuex";

export default {
    setup: async () => {
        const store = useStore();

        await store.dispatch('fetchProducts');
    },

    computed: {
        ...mapGetters({
            products: "getProducts"
        }),
    }
}
</script>
