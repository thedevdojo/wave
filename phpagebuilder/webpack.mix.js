let mix = require('webpack-mix').mix;

let authAssetsPath = 'src/Modules/Auth/resources/assets/';
mix.sass(authAssetsPath + 'sass/app.scss', 'dist/auth');

let websiteManagerAssetsPath = 'src/Modules/WebsiteManager/resources/assets/';
mix.sass(websiteManagerAssetsPath + 'sass/app.scss', 'dist/websitemanager')
    .js(websiteManagerAssetsPath + 'js/app.js', 'dist/websitemanager');

let grapesJSAssetsPath = 'src/Modules/GrapesJS/resources/assets/';
mix.copy(grapesJSAssetsPath + 'images', 'dist/pagebuilder/images')
   .sass(grapesJSAssetsPath + 'sass/app.scss', 'dist/pagebuilder')
   .sass(grapesJSAssetsPath + 'sass/page-injection.scss', 'dist/pagebuilder')
   .js(grapesJSAssetsPath + 'js/app.js', 'dist/pagebuilder')
   .js(grapesJSAssetsPath + 'js/page-injection.js', 'dist/pagebuilder');
