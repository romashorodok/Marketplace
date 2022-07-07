import { createApp } from 'vue';

require('./bootstrap');

const app = createApp({});

app.component('example-component', require('./components/ExampleComponent').default)

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => app.component(key.split('/').pop().split('.')[0], files(key).default))

app.mount('#app');
