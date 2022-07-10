import { watch, toRefs } from "vue";
import { authorize, state } from '@user';

const { user } = toRefs(state);

const localUser = localStorage.getItem('user');

if (localUser !== null) {
    // TODO: Add access toke validation if not valid logout
    authorize(JSON.parse(localUser));
}

watch(user, (next) => {
    localStorage.setItem('user', JSON.stringify(next));
});
