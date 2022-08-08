import {computed, ref, watch} from "vue";
import {useHttp} from "@/composables/useHttp";

const cart = ref(null);

const mapCart = (value) => {
    cart.value = value;
    return cart;
};

export const useCart = () => {
    const http = useHttp();

    const errors = ref([]);

    watch(http.errors, (error) => {
        console.error(error);
    });

    const fetchCart = async () => {
        if (!http.isAuthorized()) {
            console.warn('Unauthorized user trying get cart.');
            return;
        }

        try {
            const result = await http.get('/api/cart');

            return mapCart(result.cart);
        } catch (e) {
            errors.value.push(e);
        }
    };

    const addCartItem = async (productId) => {
        try {
            const result = await http.post('/api/cart/item', {productId});

            return mapCart(result.cart);
        } catch (e) {
            errors.value.push(e);
        }
    };

    const updateCartItem = async (id, fields) => {
        try {
            const result = await http.post(`/api/cart/item/${id}`, fields);

            return mapCart(result.cart);
        } catch (e) {
            errors.value.push(e);
        }
    };

    const deleteCartItem = async (id) => {
        try {
            const result = await http.del(`/api/cart/item/${id}`);

            return mapCart(result.cart);
        } catch (e) {
            errors.value.push(e);
        }
    };

    const deleteCartItems = async () => {
        try {
            const result = await http.del('/api/cart/item');

            return mapCart(result.cart);
        } catch (e) {
            errors.value.push(e);
        }
    };

    const cartItemCount = computed(() => cart.value?.count);

    return {
        fetchCart,
        updateCartItem,
        addCartItem,
        deleteCartItem,
        deleteCartItems,
        cart,
        cartItemCount,
        errors
    };
};
