import {useHttp} from "@/composables/useHttp";
import {computed, ref, watch} from "vue";

const orders = ref([]);
const errors = ref([]);
const pages = ref([]);
const page = ref(1);
const size = ref(10);

const mapOrders = (value) => {
    const {data, pages: links, currentPage} = value;

    pages.value = links;
    orders.value = data;
    page.value = currentPage;

    return orders;
};

export const useOrders = () => {
    const http = useHttp();

    const query = computed(() => new URLSearchParams({
        page: page.value,
        size: size.value
    }));

    const fetchOrders = async () => {
        if (http.isAuthorized()) {
            try {
                const {orders} = await http.get(`/api/account/orders`, query.value);

                return mapOrders(orders);
            } catch (e) {
                errors.value.push(e);
            }
        }
    };

    return {
        fetchOrders,
        orders,
        pages
    };
}
