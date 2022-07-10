import { createRouter, createWebHistory } from 'vue-router';

import Login from '@components/login';
import Home from '@components/home';

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login }
];

export default createRouter({
    routes,
    history: createWebHistory()
});
