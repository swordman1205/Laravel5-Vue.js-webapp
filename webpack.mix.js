let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js/app.js').sourceMaps();
//.babel(['public/js/temp.js'], 'public/js/app.js')
mix.sass('resources/assets/sass/app.scss', 'public/css');

let src_path = 'resources/assets/js/';

if (mix.inProduction()) {

    mix.version();

   /* mix.webpackConfig({
        module: {
            rules: [{
                test: /\.js?$/,
                exclude: /(bower_components)/,
                use: [{
                    loader: 'babel-loader',
                    options: mix.config.babel()
                }]
            }]
        }
    });*/
}

mix.webpackConfig({
    resolve: {
        alias: {
            '@components': path.resolve(__dirname, src_path + 'components/')
        }
    }
});
/*
mix.disableNotifications();*/
