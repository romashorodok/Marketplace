import {createApp} from 'vue';

import App from '@components/app';

import router from "./router";
import store from './store';

require('./bootstrap');

require('@/store');

const app = createApp(App).use(router).use(store);

store.dispatch('restoreToken')
    .then(() => app.mount('#app'));
