import {useHttp} from "@/composables/useHttp";
import {ref} from "vue";

const account = ref(null);
const orders = ref(null);

const mapAccount = (value) => {
    account.value = value;
    return account;
}

export const useAccount = () => {
    const http = useHttp();

    const errors = ref([]);

    const fetchAccount = async () => {
        if (!http.isAuthorized()) {
            console.warn('Unauthorized user trying get account details');
            return;
        }

        try {
            const {account} = await http.get('/api/account');

            return mapAccount(account);
        } catch (e) {
            errors.value.push(e);
        }
    }

    return {
        fetchAccount,
        account
    };
};
