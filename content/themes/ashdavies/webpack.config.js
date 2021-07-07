const path = require('path');

// Dev flag
const isDevelopment = true;

module.exports = {
    mode: 'none',
    entry: { index: path.resolve(__dirname, "src", "index.js") },
    mode: isDevelopment ? 'development' : 'production',
    output: {
        filename: 'scripts.min.js',
        path: path.resolve(__dirname, 'dist'),
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ["style-loader", "css-loader"]
            },
            {
                test: /\.scss$/,
                use: ["style-loader", "css-loader", "resolve-url-loader", {
                    loader: "sass-loader",
                    options: {
                        implementation: require("sass"),
                        sourceMap: true
                    }
                }]
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: ["babel-loader"]
            },
            {
                test: /\.(ttf|eot|woff|woff2|svg)$/,
                type: 'asset/resource'
            }
        ]
    },
    plugins: [

    ]
};
