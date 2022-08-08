import {createRouter, createWebHistory} from 'vue-router';

import Login from '@components/login';
import Home from '@components/home';
import Cart from '@components/cart';
import ProductDetail from '@components/product-detail';

import AccountRoute from '@components/account/router';

const routes = [
    {path: '/', component: Home},
    {path: '/cart', component: Cart},
    {name: 'product-detail', path: '/product/:id', component: ProductDetail, props: true},

    ...AccountRoute
];

export default createRouter({
    routes,
    history: createWebHistory()
});
