const path = require('path');

const jsPath = path.resolve(__dirname, './resources/js');

const componentsPath = path.join(jsPath, 'components');
const modelsPath = path.join(jsPath, 'models');

const stylePath = path.resolve(__dirname, './resources/sass');

module.exports = {
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@': jsPath,
            '@models': modelsPath,
            '@components': componentsPath,
            '@style': stylePath
        }
    }
};
