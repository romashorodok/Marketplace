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

<script setup>
import {useStore} from "vuex";
import {useRouter, useRoute} from "vue-router";
import AppProduct from "./product";
import AppPagination from '@/shared/pagination';
import {computed, onBeforeUnmount, onMounted} from "vue";

const store = useStore();
const route = useRoute();
const router = useRouter();

await store.dispatch('restoreProductQuery', route.query);
await store.dispatch('fetchProductsByFilters');

const products = computed(() => store.getters.getProducts);
const pages = computed(() => store.getters.getPages);
const currentPage = computed(() => store.getters.getCurrentPage);

onMounted(() => {
    const scrollPos = localStorage.getItem('scrollPos');
    if (scrollPos) window.scrollTo({left: 0, top: parseInt(scrollPos), behavior: 'smooth'});

    window.onbeforeunload = () => {
        localStorage.setItem('scrollPos', window.scrollY.toString());
    };
});

onBeforeUnmount(() => {
    localStorage.setItem('scrollPos', window.scrollY.toString());
});

const scrollToTop = () => window.scrollTo({top: 0, left: 0, behavior: 'smooth'});

async function changePage(page) {
    await store.dispatch('changePage', page)
        .then(scrollToTop)
        .catch(console.error);

    const query = await store.dispatch('getProductQuery');

    await router.push({path: "/", query: {...Object.fromEntries(query)}});
}
</script>
