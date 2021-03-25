let mix = require('laravel-mix');
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

mix.setPublicPath('./assets/')
	.sass('assets/app.scss', 'css')
	.js('assets/app.js', 'js')
	.tailwind('./tailwind.config.js');
