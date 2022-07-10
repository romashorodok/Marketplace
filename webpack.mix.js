const mix = require('laravel-mix');

const webpackConfig = require('./webpack.config');

mix.webpackConfig(webpackConfig)
    .js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
