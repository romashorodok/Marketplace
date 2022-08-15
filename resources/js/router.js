import {createRouter, createWebHistory} from 'vue-router';

import Home from '@components/home';
import Cart from '@components/cart';
import Checkout from '@components/checkout';
import ProductDetail from '@components/product-detail';

import AccountRoute from '@components/account/router';

const routes = [
    {path: '/', component: Home},
    {path: '/cart', component: Cart},
    {name: 'checkout', path: '/checkout', component: Checkout},
    {name: 'product-detail', path: '/product/:id', component: ProductDetail, props: true},

    ...AccountRoute
];

export default createRouter({
    routes,
    history: createWebHistory()
});
