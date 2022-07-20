import { createStore } from 'vuex';

import appModule from '@components/app/module';
import userModule from '@models/user/module';
import productModule from '@models/product/module';

export default createStore({
    modules: {
        app: appModule,
        user: userModule,
        product: productModule
    },
});
