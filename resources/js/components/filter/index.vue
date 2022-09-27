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

<script>
import {useStore} from "vuex";
import {computed} from "vue";
import {useRouter} from "vue-router";
import {debounce} from "@/shared/utils/debounce";

export default {
    setup: async () => {
        const store = useStore();
        const router = useRouter();

        await store.dispatch('fetchCategories');

        const scrollToTop = () => window.scrollTo({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });

        return {
            deferFetchProducts: debounce(() => store.dispatch('fetchProductsByFilters').then(scrollToTop), 1000),
            categories: computed(() => store.getters.getCategories),
            searchName: computed(() => store.getters.getSearchName),
            router,
            store
        }
    },

    methods: {
        async onChangeCategory(event) {
            await this.store.dispatch('addOrRemoveCategory', {
                name: event.target.name,
                isAdd: event.target.checked
            });

            this.deferFetchProducts();
        },

        async onChangeSearchProduct(event) {
            await this.store.dispatch('mutateSearchName', {
                name: event.target.value
            });

            this.deferFetchProducts();
        }
    },
}
</script>
