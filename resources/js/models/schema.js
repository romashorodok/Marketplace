import {computed, ref, watch} from "vue";

const emailPattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
const namePattern = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/;

export const ruleSignatures = {

    regex: function (value, {...args} = null) {
        const {pattern} = args;

        if (!(pattern instanceof RegExp))
            throw new Error('pattern should be instance of RegExp');

        return pattern.test(value);
    },

    must: function (value) {
        return !(value === null || value === '' || value === undefined);
    },

    same: function (value, {...args} = null) {
        const {deferValue} = args;

        return value === deferValue();
    },

    price: function (value, {...args}) {
        const {min, max} = args;

        if (Math.sign(value) <= 0) return false;

        const number = parseFloat(value);

        return number >= min && number <= max;
    }
};

class Schema {
    rules = [];
    errors = ref({});
    errorsServer = ref({});
    _name = null;
    initial = true;

    get messages() {
        return computed(() => ({...this.errors, ...this.errorsServer}));
    }

    constructor(value) {
        this.value = ref(value);

        const unwatch = watch(this.value, () => {
            this.initial = false;
            unwatch();
        });
    }

    #mapRule(name, {...args} = null) {
        this.rules.push({name, ...args});

        return this;
    }

    password(pattern = passwordPattern) {
        return this.regex(pattern);
    }

    email(pattern = emailPattern) {
        return this.regex(pattern);
    }

    name(pattern = namePattern) {
        return this.regex(pattern);
    }

    regex(pattern) {
        if (!(pattern instanceof RegExp))
            console.warn('Pattern is not regexp.');

        return this.#mapRule('regex', {pattern});
    }

    must() {
        return this.#mapRule('must')
    }

    same(deferValue) {
        return this.#mapRule('same', {deferValue})
    }

    price(min, max) {
        return this.#mapRule('price', {min, max});
    }
}

export const schemaField = (value) => new Schema(value);

export const defaultErrorMessage = {
    regex: 'Not match regex',
    must: 'Required field',
    same: 'Must be same',
    price: 'Price is incorrect'
};
