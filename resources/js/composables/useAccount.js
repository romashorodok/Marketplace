import {useHttp} from "@/composables/useHttp";
import {ref} from "vue";

const account = ref(null);
const products = ref([]);

const mapAccount = (value) => {
    account.value = value;
    return account;
}

const mapProduct = (value) => products.value = value;

export const useAccount = () => {
    const http = useHttp();

    const errors = ref([]);

    const handleHttp = async (uri) => {
        if (!http.isAuthorized()) {
            console.warn('Unauthorized user trying get account details');
            return;
        }

        try {
            return await http.get(uri);
        } catch (e) {
            errors.value.push(e);
        }
    }

    const fetchAccount = async () => {
        const {account} = await handleHttp('/api/account');
        mapAccount(account);
    }

    const fetchProducts = async () => {
        const {products} = await handleHttp('/api/account/product');
        mapProduct(products);
    }

    return {
        fetchAccount,
        fetchProducts,
        account,
        products
    };
};
