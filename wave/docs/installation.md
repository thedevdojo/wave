# Installation

- [Installing on Digital Ocean](#install-do)
- [Installing on Your Machine](#install-local)
- [Logging into Your Application](/docs/{{version}}/installation#login)

---

<a name="install-do"></a>
## Installation on Digital Ocean

![](https://cdn.devdojo.com/images/march2021/wave-on-do.png)

If you use Digital Ocean, you're in luck. You can easily start surfing the Wave(s) on DigitalOcean with our <a href="https://www.digitalocean.com/products/app-platform/" target="_blank">DO App Platform</a> integration. Not using DigitalOcean? <a href="https://m.do.co/c/dc19b9819d06" target="_blank">Get $100 in free DigitalOcean credit here</a>. 💵

Here are 4 simple steps to get started with your new SAAS on Digital Ocean.

1. [🍴 Fork The Repo](#fork-repo)
2. [🚀 Deploy to Digital Ocean](#deploy-to-do)
3. [🔐 Change Your Repo Privacy](#repo-privacy)
4. [🤓 Start Building](#start-building)

---

<a name="fork-repo"></a>
### 🍴 Fork The Repo

You may also setup your own copy of Wave by installing it to <a href="https://m.do.co/c/dc19b9819d06" target="_blank">Digital Ocean</a> by clicking the button below.

<a href="https://cloud.digitalocean.com/apps/new?repo=https://github.com/thedevdojo/wave/tree/main" target="_blank"><img src="https://www.deploytodo.com/do-btn-blue.svg" width="240" alt="Deploy to DO"></a>

<a name="install-local"></a>
## Installation on Your Local Machine

<a name="requirements"></a>
### Requirements

Wave is built on Laravel 8 and will require the minimum server requeirements [explained here](https://laravel.com/docs/deployment#server-requirements).

<a name="download"></a>
## Downloading Wave

Before you can install Wave, you will need to download the latest version from the product download page: [https://devdojo.com/scripts/php/wave](https://devdojo.com/scripts/php/wave)

> {warning} In order to download a copy of Wave with a year of updates and support you must be a **Premium Subscriber** on the DevDojo.

If you have any issues with downloading please be sure to post it in the [forums](https://devdojo.com/forums/category/wave).

<a name="unzip"></a>
## Unzipping the Files
After downloading the latest version of Wave you will need to extract the zip file and rename the folder to your choosing. You will then need to place this folder in your sites folder or the web folder for your server.

Inside the unzipped folder you should see the following file contents:

- **app**
- **bootstrap**
- **config**
- **database**
- **hooks**
- **public**
- **resources**
- **routes**
- **storage**
- **tests**
- **wave**
- .env.example
- artisan
- composer.json
- composer.lock
- phpunit.xml
- README.md
- server.php

> {info} The list items in bold represent folders and the remaining list items represent files.

Next, we are ready to begin the installation.

<a name="install"></a>
## Installing Wave

We can install Wave in 4 easy steps:

1. Create a Database
2. Copy the `.env.example` file
3. Composer Install
4. Migrate Database

We'll cover how to do each step below:

### 1. Create a New Database

During the installation we need to use a MySQL database. You will need to create a new database and save the credentials for the next step.

### 2. Copy the `.env.example` file

We need to specify our Environment variables for our application. You will see a file named `.env.example`, you will need to duplicate that file and rename it to `.env`.

Then, open up the `.env` file and update your *DB_DATABASE*, *DB_USERNAME*, and *DB_PASSWORD* in the appropriate fields. You will also want to update the *APP_URL* to the URL of your application.

```bash
APP_URL=http://wave.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wave
DB_USERNAME=root
DB_PASSWORD=
```


### 3. Add Composer Dependencies

Next, we will need to install all our composer dependencies by running the following command:

```php
composer install
```
### 4. Run Migrations and Seeds

We need to migrate our database structure into our database, which we can do by running:

```php
php artisan migrate
```
<br>
Finally, we will need to seed our database with the following command:

```php
php artisan db:seed
```
<br>

🎉 And that's it! You will now be able to visit your URL and see your Wave application up and running.

---

> {warning} For security measures you may want to regenerate your application key, be sure to run the following command below to secure your site before going live.

```php
php artisan key:generate
```

<a name="login"></a>
## Login

After installing Wave you can login with the following default credentials:

- **email**: admin@admin.com
- **password**: password

After logging in you can feel free to change the admin email, username, and password by visiting the settings section in the user menu.
