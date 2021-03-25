[< back to the main theme page](/features/themes.md)

# Theme Files

Here is a breakdown of each file/folder inside of each theme folder. Each theme `bootstrap`, `tailwind`, and `uikit` will have a very similar structure of files/folders. Folders are in **bold** text.

- **announcements**
- **assets**
- **auth**
- **blog**
- **dashboard**
- **emails**
- **fonts**
- **layouts**
- **menus**
- **notifications**
- **partials**
- **settings**
- theme_name.json - This file contains information about the specific theme. If you are viewing this inside the `bootstrap` folder it will be named `bootstrap.json`, or if you are viewing this in the tailwind theme it will be named `tailwind.json`.
- cancelled.blade.php - This is the file that will be loaded when a user tries to login and their subscription has been cancelled or is no longer active.
- home.blade.php - This is the homepage for your SAAS Application.
- options.blade.php - This is the page that will be displayed in your admin area for the theme options. Click here to learn more about using [theme options](/features/themes/options.md).
- package.json - This is the file that contains the front-end dependencies when your run `npm run watch` or `npm run production`.
- page.blade.php - This is the template for any pages that you create for your website.
- profile.blade.php - This file contains the user profile page, when someone visits a user page this is the template that will be displayed.
- trial_over.blade.php - This file will be loaded when the user's free trial has expired.
- webpack.mix.js - This is the file that contains our asset build rules. Learn more about [Laravel mix here](https://laravel.com/docs/mix).

Feel free to add as many custom files inside of the theme folder as you would like. Your SAAS is going to be different than every other application out there, so you will be responsible for maintaining the files and the structure inside of your theme folder.

Here is how you can render a view from your active theme.

```
    return view('theme::awesome');
```

This will load a file called `awesome.blade.php` inside of your active theme. So, if you are using the Tailwind theme, this fill will be located at `resources/views/themes/tailwind/awesome.blade.php`.
