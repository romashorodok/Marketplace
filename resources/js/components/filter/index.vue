<template>
    <div class="filter-wrapper">
        <section class="filter-section">
            <h4 class="section-heading">
                Categories
            </h4>
            <div v-for="category in categories" class="filter-field">
                <label class="container" v-bind:for="category.name">
                    <input type="checkbox"
                           v-bind:name="category.name"
                           v-bind:id="category.name"
                           @change="onChangeCategory"
                           :checked="isSelected(category.name)"
                    >
                    <span class="checkmark"/>
                    {{ category.name }}
                </label>
            </div>
        </section>
        <section class="filter-section body-field search-section">
            <input :value="searchName" placeholder="Search product" @input="onChangeSearchProduct"/>
        </section>
    </div>
</template>

<script setup>
import {useStore} from "vuex";
import {computed} from "vue";
import {useRouter} from "vue-router";
import {debounce} from "@/shared/utils/debounce";

const store = useStore();
const router = useRouter();

const scrollToTop = () => window.scrollTo({
    top: 0,
    left: 0,
    behavior: 'smooth'
});

const selectedCategories = computed(() => store.getters.getFilterCategories);
const categories = computed(() => store.getters.getCategories);
const searchName = computed(() => store.getters.getSearchName);

const deferFetchProducts = debounce(async () => {
    await store.dispatch('changePage', 1);
    const query = await store.dispatch('getProductQuery');
    await router.push({ query: {...Object.fromEntries(query)}});
    store.dispatch('fetchProductsByFilters').then(scrollToTop)
}, 1000);

async function onChangeCategory(event) {
    await store.dispatch('addOrRemoveCategory', {
        name: event.target.name,
        isAdd: event.target.checked
    });
    deferFetchProducts();
}

async function onChangeSearchProduct(event) {
    await store.dispatch('mutateSearchName', {
        name: event.target.value
    });
    deferFetchProducts();
}

function isSelected(name) {
    return selectedCategories.value.indexOf(name) > -1;
}
</script>
