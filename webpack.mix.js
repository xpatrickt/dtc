const webpack = require('webpack');
const path = require('path');
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
let plugins = 'resources/js/plugins/';

mix.webpackConfig({
    resolve: {
        alias: {
            'jquery$': path.resolve(path.join(__dirname, 'node_modules', 'jquery')),
        }
    },
    plugins: [
        new webpack.ProvidePlugin({
            jQuery: 'jquery',
            $: 'jquery',
            'window.jQuery': 'jquery',
        }),
    ],
});
mix.options({
    imgLoaderOptions: {
        enabled: false
    },
});

mix.js('resources/js/app.js', 'public/js').combine([
    plugins + 'moment/moment.min.js',
    ], 'public/js/plugins.js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');