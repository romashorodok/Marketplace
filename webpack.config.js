const path = require('path');
const dotenv = require('dotenv');
const webpack = require('webpack');

const jsPath = path.resolve(__dirname, './resources/js');

const componentsPath = path.join(jsPath, 'components');
const modelsPath = path.join(jsPath, 'models');

const stylePath = path.resolve(__dirname, './resources/sass');

const env = dotenv.config().parsed;

module.exports = {
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@': jsPath,
            '@models': modelsPath,
            '@components': componentsPath,
            '@style': stylePath
        }
    },

    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                STRIPE_SECRET: JSON.stringify(env['STRIPE_SECRET'])
            }
        })
    ]
};
