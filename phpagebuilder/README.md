
![PHPagebuilder](https://user-images.githubusercontent.com/5946444/67637466-445d4400-f8db-11e9-86ce-6e62ce7ede8a.png)

# PHPagebuilder
> PHPagebuilder is a drag and drop pagebuilder to manage pages in any PHP project.

PHPagebuilder can be used as an ultra lightweight CMS to quickly spin up new websites, or can be integrated into existing projects or your favorite frameworks (such as [this version](https://github.com/HansSchouten/Laravel-Pagebuilder) for Laravel). The server-side code does not depend on any other libraries and is blazing fast. It includes an optional website manager with a [Bootstrap UI](https://getbootstrap.com) and integrates the most popular open source drag and drop pagebuilder: [GrapesJS](https://grapesjs.com/). This package is made with customization in mind, allowing you to configure, disable or replace any of its modules.

## Table of Contents
- [How does it help me?](#how-does-it-help-me)
- [Features](#features)
- [Installation](#installation)
- [Customization](#customization)

## How does it help me?
Whether you are a novice or an experienced web developer, PHPagebuilder can make your life easier if you find yourself in any of the following:

- You just want to create a basic website that you can easily manage from any device.
- You get lost installing, configuring, updating or simply using feature abundant CMS systems like Wordpress, Drupal.
- You don't like the limited page editors in CMSs you've tried.
- You want to build a custom website for a client within a few hours.
- Your clients get lost in feature abundant admin panels like Drupal.
- You hate to rely on messy plugins for features you can write in no-time in plain PHP yourself, but still want to have some admin functionality to allow other people to manage the websites you create.
- You would like to have advanced functionality (search functionality, views displaying data of remote sources, etc.) easily manageable by your clients.

## Features

### Page Builder
PHPagebuilder features a page builder built on [GrapesJS](https://grapesjs.com/).
![PageBuilder](https://user-images.githubusercontent.com/5946444/70818285-97c81a80-1dd3-11ea-84b0-2a6ff3a8765a.png)

### Website Manager
A basic website manager is included with a [Bootstrap](https://getbootstrap.com/) UI. This website manager offers basic functionality to add or remove pages and to edit page settings like page title or URL. Clicking the edit button will open the page builder.
![Website Manager](https://user-images.githubusercontent.com/5946444/67484882-4029f000-f669-11e9-9a1f-8a0e1c53e308.jpg)

You don't want to use the website manager? No worries, it is included for people who want to use PHPagebuilder directly out of the box. Read [here](#customize-the-website-manager) how to disable or replace the website manager.

## Installation

To install PHPagebuilder you either follow the quick start path, for quickly spinning up a new website, or you integrate it in your own framework or favorite project structure.

### Requirements

- PHP version >= 7
- A cool project

### Quick start :rocket:

If you want to quickly start a new project with drag and drop page management functionality, you can download the [boilerplate](https://github.com/HansSchouten/PHPagebuilder-boilerplate) project structure and follow the steps over there.

### Integrate into existing project or framework

#### Add code with Composer
If you are using Composer for managing PHP dependencies, you can simply run:
```
composer require hansschouten/phpagebuilder
```

Next, the PHPagebuilder can be initialised using the following PHP code:
```PHP
require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

$builder = new PHPageBuilder\PHPageBuilder($config);
$builder->handleRequest();
```

`config.php` should contain a copy of `config/config.example.php`, filled with your settings. 

#### Add code without Composer
Are you not using Composer? No worries, this project is written in plain PHP without any dependencies, so it can be directly included in any PHP project!

Just download the latest release into a separate folder inside your project (we named the folder: `phpagebuilder`).

Next, you simply include the following code in the PHP file that should launch the page builder:

```PHP
$installationFolder = __DIR__ . '/phpagebuilder';
require_once $installationFolder . '/src/Core/helpers.php';
spl_autoload_register('phpb_autoload');

$config = require __DIR__ . '/config.php';

$builder = new PHPageBuilder\PHPageBuilder($config);
$builder->handleRequest();
```

`config.php` should contain a copy of `config/config.example.php`, filled with your settings.

### Configure a database
If you use PHPagebuilder out of the box, it requires a database for storing your pages. Just create a database and run the queries from `config/create-tables.sql`. Next, add the database credentials to your config file.

## Create a theme
The `config.php` contains a config key `theme` in which a `themes_folder` and `active_theme` are specified. To create a new theme, add a new folder to the configured themes folder. The name of this folder will be the identifier of the theme, which can be used to select the theme in the `theme > active_theme` configuration.

A theme should have the following folder structure:

- **/blocks**  
The blocks folder contains a sub folder for each block that can be used in the page builder. The folder of a single block contains a `view.php` or a `view.html`. If a `view.html` is used, the block content (the html elements) are fully editable inside the page builder. If `view.php` is used, the block can be rendered with server-side logic (PHP) and hence the html content itself cannot be changed from within the page builder. 
- **/layouts**  
The layouts folder contains a sub folder for each page layout. A page layout contains a `view.php` file which defines the base html structure with all stylesheets and scripts needed for the blocks dragged on this type of page. Each layout requires the string: `<?= $body ?>` on the location at which the html blocks need to be added in the page layout. 
- **/public**  
The public folder contains all assets (css, javascript, images, etc) that should be publicly accessible. The `[theme-url]` shortcode can be used to point to a file in the public folder of the currently active theme. For instance the file `public/css/style.css` can be loaded via `<link rel="stylesheet" href="[theme-url]/css/style.css">`.

### Blocks inside blocks
#### Include a block into a block or layout file
A shortcode can be used to include a block inside another block or into a layout file. For instance, adding the shortcode: `[block slug="header"]` to `layouts/master/view.php` includes the block: `blocks/header/view.php` inside each page that uses the `master` layout.

#### Nested blocks in the page builder
To allow dropping blocks into a block in the page builder, a blocks container should be added. To add a blocks container, simply add `[blocks-container]` at the desired location in a block view file. The following html syntax allows dropping blocks inside a bootstrap container element:
```html
<div class="container">
    [blocks-container]
</div>
```
An alternative method is adding the `phpb-blocks-container` attribute to a html element, as shown in this example:
```html
<div class="row">
    <div class="col-md-6" phpb-blocks-container>
    </div>
    <div class="col-md-6" phpb-blocks-container>
    </div>
</div>
```

## Extending PHPageBuilder

PHPageBuilder allows you to create new blocks and layouts for your theme very easily. This is great for building specific websites & templates, however if you use PHPageBuilder in a CMS environment, you probably want to provide Plugins / Modules the ability to create their own blocks without modifying your theme's pre-existing components.

PHPageBuilder allows you to register blocks, layouts and assets (CSS, JS) from Plugins, Composer Packages or through any other environment.

### Adding a New Block

```php
/**
 * @param string $slug - A Unique identifier for the block. Prefix author to avoid conflict.
 * @param string $directoryPath - Path to the directory of the Block.
 */
\PHPageBuilder\Extensions::registerBlock($slug, $directoryPath);

// Registering an example block:

\PHPageBuilder\Extensions::registerBlock('foo-navbar', MY_PLUGIN_DIRECTORY . '/blocks/foo-navbar');
```

### Adding a New Layout

```php
/**
 * @param string $slug - A Unique identifier for the layout. Prefix author to avoid conflict.
 * @param string $directoryPath - Path to the directory of the Layout.
 */
\PHPageBuilder\Extensions::registerLayout($slug, $directoryPath);

// Registering an example layout:

\PHPageBuilder\Extensions::registerLayout('foo-master', MY_PLUGIN_DIRECTORY . '/layouts/foo-master');
```

### Adding Assets (CSS & JS)
```php
/**
 * @param string $src                           - URL of the Asset file.
 * @param string $type                          - 'style' or 'script' 
 * @param string $location                      - 'header' or 'footer'
 * @param array['$key' => '$value'] $attributes - Array of attributes to add to the tag.
 */
\PHPageBuilder\Extensions::registerAsset($src, $type, $location, $attributes = []);

// Registering assets:

\PHPageBuilder\Extensions::registerAsset('/assets/bootstrap.css', 'style', 'header');
\PHPageBuilder\Extensions::registerAsset('/assets/alpine.min.js', 'script', 'header', [
    'defer' => true
]);
```

Blocks & Layouts are created in the same manner as described in [Create A Theme](#create-a-theme), but they aren't limited to any theme directory.

## Customize PHPageBuilder

PHPagebuilder is build with customization in mind. It comes with an extensive example config file in wich you can easily adapt the pagebuilder to your needs.

PHPagebuilder consists of four modules (Auth, Website Manager, Pagebuilder, Routing) each of which you can disable or replace with your own implementation. To replace a module with your own implementation, implement the corresponding Contract and replace the default class by your own class in the config file.

### Customize the Website Manager
#### Disable the module
Do you already have admin login functionality in your project? Then you can disable the website manager module by setting  `use_website_manager` to `false` in your config. Next, you use or implement the page create/edit/remove functionality in your project and then directly launch the pagebuilder. You can render the pagebuilder from your project by using the `PHPageBuilder\Modules\GrapesJS\PageBuilder` class.

#### Replace the module
If you want to use the CMS routing functionality of PHPagebuilder, but you want to have a different website manager, you can replace the website manager for your own implementation. Make sure leave `use_website_manager` to `true` in your config, implement the WebsiteManagerContract and add your own class to your config file.
