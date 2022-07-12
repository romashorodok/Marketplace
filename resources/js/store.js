import { createStore } from 'vuex';

import appModule from '@components/app/module';
import userModule from '@user/module';

export default createStore({
    modules: {
        app: appModule,
        user: userModule
    },
});
