![Tests](https://github.com/Team-Tea-Time/laravel-forum/actions/workflows/tests.yml/badge.svg) ![phpcs](https://github.com/Team-Tea-Time/laravel-forum/actions/workflows/phpcs.yml/badge.svg) [![StyleCI](https://github.styleci.io/repos/28139801/shield?style=flat&branch=5.0)](https://github.styleci.io/repos/28139801?branch=5.0) ![Packagist Downloads](https://img.shields.io/packagist/dm/riari/laravel-forum) ![Packagist License](https://img.shields.io/packagist/l/riari/laravel-forum)

![Laravel Forum Logo](./logo.png)

**Complete documentation is available on [teamteatime.net](https://www.teamteatime.net/docs/laravel-forum/5/).**

## Versions

| **Laravel version** | **Package version** | **PHP version** |
|---------------------|---------------------|-----------------|
| 9                   | ^5.3                | ^8.0            |
| 6 - 8               | ^5.0                | ^7.4            |
| 5                   | ^4.0                | ^7.4            |

See the [support policy in the Laravel docs](https://laravel.com/docs/9.x/releases#support-policy) for more information about Laravel release versions, their supported PHP versions, and how long they receive bug & security fixes.

## Installation

### Step 1: Install the package

Install the package via composer:

```
composer require riari/laravel-forum:~5.0
```

[Package Discovery](https://laravel.com/docs/8.x/packages#package-discovery) should take care of registering the service provider automatically, but if you need to do so manually, add the service provider to your `config/app.php`:

```php
TeamTeaTime\Forum\ForumServiceProvider::class,
```

### Step 2: Publish the package files

Run the vendor:publish command to publish the package config, translations and migrations to your app's directories:

`php artisan vendor:publish`

### Step 3: Update your database

Run your migrations:

`php artisan migrate`

### Additional steps

#### Configuration

Several configuration files are published to your application's config directory, each prefixed with `forum.`. Refer to these for a variety of options for changing the behaviour of the forum and how it integrates with key parts of your application code.

> You may need to modify the `forum.integration.user_name` config option according to your user model. This specifies which attribute on the user model should be used as a display name in the forum views.

#### Translations

Laravel Forum currently supports 15 languages: German, English, Spanish, French, Italian, Dutch, Romanian, Russian, Thai, Turkish, Serbian, Portuguese (Brazil), Swedish, Chinese, and Indonesian. The translation files are published to `resources/lang/vendor/forum/{locale}`. **Some new language strings have been introduced in 5.0 but not yet translated; PRs to translate these would be greatly appreciated.**

## Development

If you wish to contribute, an easy way to set up the package for local development is [Team-Tea-Time/laravel-studio](https://github.com/Team-Tea-Time/laravel-studio), which is set up to load a local working copy of this repository (see the [readme](https://github.com/Team-Tea-Time/laravel-studio/blob/6.x/readme.md#usage) for usage details).

### Running tests

Bring up the MySQL service:

```bash
docker-compose up -d mysql
```

Install Composer dependencies:

```bash
docker-compose run --rm composer install
```

Run the phpunit container to execute tests:

```bash
docker-compose run --rm phpunit
```

### Seeding

The package tables can be seeded with sample categories, threads, posts, and a user via `forum:seed`:

```bash
docker-compose exec php-fpm php artisan forum:seed
```
