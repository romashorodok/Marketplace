import Account from "./index";

import Profile from './components/profile';
import Product from './components/products';
import Order from './components/orders';

const routes = [
    {
        path: '/account',
        component: Account,
        children: [
            {path: 'profile', component: Profile},
            {path: 'products', component: Product},
            {name: 'orders', path: 'orders', component: Order}
        ]
    }
];

export default routes;
