// Dev flag
const isDevelopment = process.env.NODE_ENV === 'development'

const path = require('path');

module.exports = {
    mode: 'none',
    entry: './src/index.js',
    mode: isDevelopment ? 'development' : 'production',
    output: {
        filename: 'scripts.min.js',
        path: path.resolve(__dirname, 'dist'),
        clean: true,
    },
    module: {},
    plugins: []
};
