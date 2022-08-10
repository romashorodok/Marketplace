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

    required: function (value) {
        return value === null || value === '';
    },

    same: function (value, {...args} = null) {
        const {deferValue} = args;

        return value === deferValue();
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
        return this.regex(emailPattern);
    }

    name(pattern = namePattern) {
        return this.regex(pattern);
    }

    regex(pattern) {
        if (!(pattern instanceof RegExp))
            console.warn('Pattern is not regexp.');

        return this.#mapRule('regex', {pattern});
    }

    required() {
        return this.#mapRule('required')
    }

    same(deferValue) {
        return this.#mapRule('same', {deferValue})
    }
}

export const schemaField = () => new Schema();

export const defaultErrorMessage = {
    regex: 'Not match regex',
    required: 'Required field',
    same: 'Must be same'
};
