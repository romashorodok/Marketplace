import {ref} from "vue";
import store from "@/store";

export const useHttp = () => {
    const result = ref(null);
    const errors = ref(null);

    const token = store.getters.getToken ?? null;

    const config = () => ({
        headers: {
            Authorization: `Bearer ${token}`
        }
    });

    const get = async (url) => {
        try {
            const resp = await axios.get(url, config());

            result.value = await resp.data;

            return result.value;
        } catch (e) {
            errors.value = e;
            result.value = null;
        }
    };

    const post = async (url, data) => {
        try {
            const resp = await axios.post(url, data, config());

            result.value = await resp.data;

            return result.value;
        } catch (e) {
            errors.value = e;
            result.value = null;
        }
    };

    const del = async (url) => {
        try {
            const resp = await axios.delete(url, config());

            result.value = await resp.data;

            return result.value;
        } catch (e) {
            errors.value = e;
            result.value = null;
        }
    };

    const isAuthorized = () => token !== null;

    return {
        errors,
        get,
        post,
        del,
        isAuthorized
    };
};
