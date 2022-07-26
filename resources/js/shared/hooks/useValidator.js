const emailPattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
const namePattern = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/;

export default ({ target , rules, messages }) => {

    const deleteField = (name, type) => {
        if (!messages[name]) return;

        return Object.assign(messages, delete messages[name][type]);
    };

    const updateField = (name, type, message) => {
        /**
         * { password: { ...restPasswordFields, regex: "my message", } }
         */
        return Object.assign(messages, {[name]: {...messages[name], [type]: message}});
    };

    const email = (rule) => {
        regex({...rule, regex: emailPattern });
    };

    const name = (rule) => {
        regex({...rule, regex: namePattern });
    };

    const password = (rule) => {
        regex({...rule, regex: passwordPattern })
    };

    const regex = ({ type, name, message, regex }) => {
        if (regex instanceof RegExp && regex.test(target))
            deleteField(name, type);
        else
            updateField(name, type, message);
    };

    const required = ({ type, name, message }) => {
        if (target === null || target === '')
            updateField(name, type, message);
        else
            deleteField(name, type);
    };

    const callback = ({ name, message, callback, alias }) => {
        if (!callback(target))
            updateField(name, alias, message);
        else
            deleteField(name, alias);
    };

    return {
        validate: () => {
            rules.forEach(rule => {
                switch (rule.type) {
                    case 'required': required(rule); break;
                    case 'regex': regex(rule); break;
                    case 'callback': callback(rule); break;
                    case 'email': email(rule); break;
                    case 'name': name(rule); break;
                    case 'password': password(rule); break;
                }
            });
        }
    };
};

export function isValidMessages (messages) {
    let isValid = true;

    for(const message in messages) {
        if (Object.keys(message).length !== 0) {
            isValid = false;
            break;
        }
    }

    return isValid;
}
