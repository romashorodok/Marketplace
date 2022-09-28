<template>
    <Suspense>
        <template #default>
            <app-filter />
        </template>
        <template #fallback>
            Loading...
        </template>
    </Suspense>
    <div class="content-wrapper">
        <Suspense>
            <template #default>
                <app-product-list />
            </template>
            <template #fallback>
                Loading...
            </template>
        </Suspense>
    </div>
</template>

<script>
import AppProductList from '@components/product-list';
import AppFilter from '@components/filter';

import { useStore } from "vuex";

export default {
    setup: async  () => {
        const store = useStore();

        await store.dispatch('fetchCategories');
    },

    components: { AppProductList, AppFilter }
}
</script>
