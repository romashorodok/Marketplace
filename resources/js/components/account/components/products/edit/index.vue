<template>
    <div>
        <form @submit.prevent="checkForm">
            <app-upload :image="schema.image.value"
                        :messages="schema.image.messages"
                        @upload="file => schema.image.value = file"/>
            <app-field label="Name"
                       v-model:model="schema.name.value"
                       :messages="schema.name.messages"/>
            <app-field label="Price"
                       v-model:model="schema.price.value"
                       :messages="schema.price.messages"/>

            <app-select v-model:model="schema.category.value" :options="categories"
                        :messages="schema.category.messages"/>

            <div class="body-field">
                <app-textarea class="description-text text"
                              v-model:text="schema.description.value"
                              :placeholder="'Description'"/>
            </div>
            <app-button class="">Update</app-button>
        </form>
    </div>
</template>

<script setup>
import {computed, defineProps, onMounted, watch} from "vue";
import {defSchema, useForm} from "@/composables/useForm";
import {schemaField} from "@models/schema";
import AppField from '@/shared/field';
import AppTextarea from '@/shared/textarea';
import AppButton from '@/shared/button';
import AppUpload from '@/shared/upload';
import AppSelect from '@/shared/select';
import {useHttp} from "@/composables/useHttp";
import {useStore} from "vuex";

const props = defineProps({
    create: {default: true, type: [Boolean, String]},
    id: {type: [String, undefined], default: null}
});

const http = useHttp();

const product = await computed(async () => {
    return props.id ?
        (await http.get(`/api/product/${props.id}`)).product :
        null;
}).value;
const update = computed(() => !(props.create && !props.id)).value;

const schema = defSchema({
    name: schemaField(product?.name).must(),
    price: schemaField(product?.price).price(0.01, 9999999.99),
    description: schemaField(product?.description),
    image: schemaField(product?.image.path).must(),
    category: schemaField(product?.category.name).must()
});

const messages = {
    price: {price: 'Price must be higher then 0.01 and maximum then 9999999.99'},
    name: {must: 'Required product name'},
    description: {must: 'Required description'},
    image: {must: 'Required image'},
    category: {must: 'Please select category'}
};

const {validate, hasError, resetServerErrors} = useForm(schema, messages);
const store = useStore()

const categories = await computed(async () => {
    await store.dispatch('fetchCategories');
    return store.getters.getCategories;
}).value;

const selectedCategory = computed(() => categories.find(item => item.name === schema?.category.value));

watch(schema, () => validate(true));

const checkForm = async () => {
    validate();

    if (!hasError()) {
        resetServerErrors();

        const data = new FormData();
        data.set('image', schema?.image.value);
        data.set('category_id', selectedCategory.value.id);
        data.set('price', schema?.price.value);
        data.set('description', schema?.description.value);
        data.set('name', schema?.name.value);

        if (update) {
            data.set('id', product?.id);
            await http.post(`/api/account/product/${product?.id}`, data);
        } else
            await http.post(`/api/account/product`, data);
    }
};
</script>
