import { createRouter, createWebHistory } from 'vue-router';

import login from './components/login';
import home from './components/home';

const routes = [
    { path: '/', component: home },
    { path: '/login', component: login }
];

export default createRouter({
    routes,
    history: createWebHistory()
});
