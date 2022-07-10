const path = require('path');
const webpack = require('webpack');

const jsPath = path.resolve(__dirname, './resources/js');

const componentsPath = path.join(jsPath, 'components');
const userPath = path.join(jsPath, 'user');

module.exports = {
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@': jsPath,
            '@user': userPath,
            '@components': componentsPath
        }
    }
};
