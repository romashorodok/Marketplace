import {reactive} from "vue";
import {ruleSignatures, defaultErrorMessage} from "@/models/schema";

export function defSchema({...rules}) {
    const fields = reactive({});

    Object.entries(rules).forEach(([key, field]) => {
        fields[key] = field;
        field._name = key;
    });

    return fields;
}

export function useForm(schema, errorMessages = defaultErrorMessage) {

    const applyRule = (rule, value) => {
        const signature = ruleSignatures[rule.name];

        if (!signature) throw new Error('Cannot find rule');

        return signature(value, rule);
    };


    const validate = () => {
        for (const field of Object.values(schema)) {
            for (const rule of field.rules) {
                try {
                    const result = applyRule(rule, field.value);
                    if (!result)
                        field.errors[rule.name] = errorMessages.hasOwnProperty(field._name)
                            ? errorMessages[field._name][rule.name]
                            : defaultErrorMessage[rule.name];
                    else
                        delete field.errors[rule.name];

                } catch (e) {
                    rule.errors[rule.name] = e;
                }
            }
        }
    };

    const hasError = () => {
        for (const field of Object.values(schema)) {
            if (Object.keys(field.errors).length !== 0)
                return true;
        }
        return false;
    };

    const setServerErrors = (errors) => {
        const fieldNames = Object.keys(errors);

        for (const name of fieldNames) {
            const field = schema[name];
            field.errorsServer[name] = errors[name];
        }
    };

    const resetServerErrors = () => {
        for (const fields of Object.values(schema)) {
            fields.errorsServer = {};
        }
    }

    return {validate, hasError, setServerErrors, resetServerErrors};
}
