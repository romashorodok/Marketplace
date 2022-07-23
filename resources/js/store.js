import { createStore } from 'vuex';

import appModule from '@components/app/module';
import filterModule from '@components/filter/module';

import userModule from '@models/user/module';
import productModule from '@models/product/module';
import categoryModule from '@models/category/module';

export default createStore({
    modules: {
        app: appModule,
        filter: filterModule,

        user: userModule,
        product: productModule,
        category: categoryModule
    },
});
