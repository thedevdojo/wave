# Installation

- [Requirements](/docs/{{version}}/installation#requirements)
- [Download](/docs/{{version}}/installation#download)
- [Unzip Files](/docs/{{version}}/installation#unzip)
- [Install](/docs/{{version}}/installation#install)
- [Login](/docs/{{version}}/installation#login)

---

<a name="requirements"></a>
## Requirements

Wave has been crafted with the latest version of Laravel (6) and Voyager (1), which have a few server requirements before installing. Here are the following Server Requirements:

- PHP >= 7.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

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
