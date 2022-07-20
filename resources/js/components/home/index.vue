<template>
    <div class="filter-wrapper"></div>
    <Suspense>
        <template #default>
            <app-product-list />
        </template>
        <template #fallback>
            Loading...
        </template>
    </Suspense>
</template>

<script>
import AppProductList from '@components/product-list';

export default {
    components: { AppProductList }
}
</script>
