<template>
    <div class="filter-wrapper">
        <section class="filter-section">
            <h4 class="section-heading">
                Categories
            </h4>
            <div v-for="category in categories">
                <input type="checkbox"
                       v-bind:name="category.name"
                       v-bind:id="category.name"
                       @change="onChangeCategory"
                >
                <label class="app-btn-link" v-bind:for="category.name">
                    {{ category.name }}
                </label>
            </div>
        </section>
        <section class="filter-section">
        </section>
    </div>
</template>

<script>
import { useStore } from "vuex";
import { computed } from "vue";
import { useRouter } from "vue-router";

export default {
    setup: async () => {
        const store = useStore();
        const router = useRouter();

        await store.dispatch('fetchCategories');

        return {
            categories: computed(() => store.getters.getCategories),
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

            await this.store.dispatch('fetchProductsByFilters');

        }
    },
}
</script>
