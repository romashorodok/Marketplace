export const debounce = (callback, timeout = 1000) => {
    let state;

    return (...args) => {
        clearTimeout(state);

        state = setTimeout(() => callback(...args), timeout);
    };
};
