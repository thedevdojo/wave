# Themes

Wave has full theme support, which means you can separate your views into separate themes. This will make it easier to create new versions of your site and revert back if needed. This will also help you separate a lot of your back-end logic with the front-end.

In this section you will learn where the themes are located and how to activate a specific theme.

## Theme Location

Every theme is located inside of the `resources/views/themes` folder. You will see inside of that folder are 3 theme folders, which are `bootstrap`, `tailwind`, and `uikit`. Each theme is also responsible for managing their own assets. In each of those folders you will find a `package.json` which contains the front-end dependencies to run webpack and build each one. Learn more about [theme assets here](/docs/features/themes/assets.md).

## Activating Themes

If you are logged in as an admin user and you visit visit the <a href="/admin/themes" target="_blank">`/admin/themes`</a> section of your application you’ll see the current themes available in your app.

![](../..//wave/img/docs/1.0/admin-themes-1.png)

To activate a Theme you can simply click the Activate button for the current theme you would like to activate, and that will be the current active theme.


For more information on customizing and modifying themes, be sure to visit the following sections below.

- [Themes Files](/features/themes/files.md)
- [Theme Options](/docs/features/themes/options.md)
