const { mix } = require('laravel-mix');

mix.js('resources/assets/js/landing.js', 'public/js')

mix.js('resources/assets/js/admin/admin.js', 'public/js/admin')
  .js('resources/assets/js/employee/app.js', 'public/js')
  .extract(['vue']);

mix.sass('resources/assets/sass/admin/admin.scss', 'public/css')
  .sass('resources/assets/sass/employee/app.scss', 'public/css')
  .sass('resources/assets/sass/landing/style.scss', 'public/css');
