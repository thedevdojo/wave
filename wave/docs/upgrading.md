# Upgrading

Upgrading Wave is very simple. Follow the steps below to upgrade.

- [Upgrade Steps](#steps)
- [Theme Upgrade](#theme-upgrade)

---

<a name="steps"></a>
### Upgrade Steps

Download a copy of the latest version. In the root folder you should see another folder named `wave`, you can simply replace this folder with the `wave` folder in your project.

You will then need to re-autoload your dependencies by running:

```php
composer dump-autoload
```

You may also need to clear the cache by running:

```php
php artisan cache:clear
```

And you should be updated to the latest version :)

<a name="theme-upgrade"></a>
### Theme Upgrade

Upgrading the core of Wave is very simple; however, upgrading your current theme may be a bit more complicated based on the modifications you have made.

Typically you will not need to upgrade your theme, it will have all base features and you can build on top of those. If there is a new feature that gets released which has corresponding views, then you may have a few files that you need to manually move into your project.
