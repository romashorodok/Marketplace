import Account from "./index";

import Profile from './components/profile';
import Product from './components/products';
import Edit from './components/products/edit';
import Order from './components/orders';

const routes = [
    {
        path: '/account',
        component: Account,
        children: [
            {path: 'profile', component: Profile},
            {path: 'products', component: Product},
            {path: 'products/edit', component: Edit},
            {name: 'editById', path: 'products/edit/:id', component: Edit, props: true},
            {name: 'orders', path: 'orders', component: Order},
        ]
    }
];

export default routes;
