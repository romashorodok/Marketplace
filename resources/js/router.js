import {createRouter, createWebHistory} from 'vue-router';

import Login from '@components/login';
import Home from '@components/home';

import AccountRoute from '@components/account/router';

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },

    ...AccountRoute
];

export default createRouter({
    routes,
    history: createWebHistory()
});
