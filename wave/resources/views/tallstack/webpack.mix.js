const mix = require('laravel-mix');
const glob = require('glob-all');

require('laravel-mix-tailwind');
require('laravel-mix-purgecss');


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

mix.copy('./tallstack.jpg', '../../../../public/themes/tallstack/');

mix.setPublicPath('../../../../public/themes/tallstack/')
	.sass('assets/sass/app.scss', 'css')
	.js('assets/js/app.js', 'js')
	.tailwind('./tailwind.config.js')
