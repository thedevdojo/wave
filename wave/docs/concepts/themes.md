# Themes

In this section we'll teach you how to create a new theme, reference a theme view, and add theme options for your particular theme.

- [Create a Theme](#create-theme)
- [Theme Views](#theme-views)
- [Theme Options](#theme-options)
- [Theme Assets](#theme-assets)

---

<a name="create-theme"></a>
## Create a Theme

In order to create a theme, you will need to create a new folder inside of `resources/views/themes`. Let's call this folder `sample-theme`. Then inside of this folder we need a matching filename called `sample-theme.json`:

```json
{
    "name": "Sample Theme",
    "version": "1.0"
}
```

Now, if you visit `/admin/themes`, you'll see this new theme available to activate.

> You may see a broken image in the admin if you do not have an image for that theme. Inside of your theme folder you will need a `.jpg` file with the same name as the folder `sample-theme.jpg` (recommended 800x500px)

<a name="theme-views"></a>
## Theme Views

After activating a theme you can render any view `file.blade.php` or `file.php` by calling `theme::file`.

For instance, if we created a new file inside our sample theme at `resources/views/themes/sample-theme/home.blade.php` we could then return the view in a controller like so:

```php
public function home(){
    return view('theme::home');
}
```

Or, you could do this inside of your route file:

```php
Route::view('/', 'theme::home');
```

<a name="theme-options"></a>
## Theme Options

Every theme can include options such as logo, color scheme, etc... You can choose to program any amount of options into your theme.

In order to create an *options* page for your theme you need to create a new file inside your theme folder called `options.blade.php`. As an example take a look at the Tailwind theme options located at `resources/views/themes/tailwind/options.blade.php`, you will see a snippet similar to:

```php
<div id="branding" class="tab-pane fade in active">

    {!! theme_field('image', 'logo', 'Site Logo') !!}

    {!! theme_field('image', 'footer_logo', 'Footer Logo') !!}

</div>
```

This will allow us to create some dynamic theme fields. This is powered by the [DevDojo Themes Package](https://github.com/thedevdojo/themes). You can easily create input fields for your theme options.

For more information about the different type of fields be sure to visit the themes package located at [https://github.com/thedevdojo/themes](https://github.com/thedevdojo/themes)

<a name="theme-assets"></a>
## Theme Assets

The current themes use Webpack to mix their assets. In order to run the assets for each theme you will need to go into the theme folder and run:

```javascript
npm run watch
```

Additionally, to compile the assets and make them production ready, you'll need to use:

```javascript
npm run production
```

> Before compiling assets in each theme you may need to include the asset dependencies by running `npm install` inside of the theme folder.

---

Now, you can quickly and easily create configurable themes for your application 🎉
