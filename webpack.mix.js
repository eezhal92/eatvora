const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/admin/admin.js', 'public/js/admin')
  .js('resources/assets/js/employee/app.js', 'public/js')
  .extract(['vue']);

mix.sass('resources/assets/sass/admin/admin.scss', 'public/css')
  .sass('resources/assets/sass/employee/app.scss', 'public/css');
