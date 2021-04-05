# File Structure

In this section of the documentaion we will briefly discuss the file structure of Wave so that way you can better familiarize yourself with how Wave functions.

- [Root Folder Structure](#root-structure)
- [Wave Folder Structure](#wave-structure)

---

<a name="root-structure"></a>
### Root Folder Structure

If you are already familiar with Laravel a lot of this will seem very familiar. Let's discuss each main folder/file in your Wave application.

> bold text indicates a folder, non-bold indicates a file.

- **app** -
This directory will hold most (if not all) of your application logic including Models, Controllers, Services, and many other classes.

- **bootstrap** -
This folder contains files to bootstrap (start-up) Laravel.

- **config** -
This folder will container many of the global configurations in our application.

- **database** -
This folder contains database files such as migrations and seeds.

- **public** -
This public folder contains many of the applications public assets such as images, stylesheets, and scripts.

- **resources** -
This folder contains the views inside of our application and it also contains our theme files located inside of `resources/views/themes` folder.

- **routes** -
This folder will contain all the routes files for our application.

- **storage** -
This folder is used to store session information, cache files, and logs.

- **tests** -
This folder contains files that we use to test the logic of our application.

- **vendor** -
This folder contains the composer (third-party) dependencies we use in our application.

- **wave** -
This folder contains all the logic for the wave application. We'll be talking more about the contents of this folder in the next step.

- *.env.example* -
This is an example environment file. Duplicate this file and rename it to `.env` in order to make it your application environment file.

- *artisan* -
This is the artisan command we use to run CLI commands, such as `php artisan tinker` and many others.

- *composer.json* -
This is the file that tells our application what third-party composer packages we want to include.

- *composer.lock* -
This is the file that contains the exact version number of the composer packages included in our application.

- *deploy.json* -
This file contains information to deploy your app to the DigitalOcean app marketplace.

- *docker-compose.yml* -
This is a docker compose file that will allow you to run your app using <a href="https://laravel.com/docs/sail" target="_blank">Laravel Sail</a>.

- *phpunit.xml* -
This file is used to store information in order to run tests and test the functionality of our application.

- *README.md* -
This is a quick Readme Markdown file.

- *server.php* -
This is the file that allows us to create a quick local PHP server.

<a name="wave-structure"></a>
### Wave Folder Structure

The Wave folder structure is pretty simple and straight forward. If you look inside the `wave` folder located in the application folder you will see the following folder structure:

- **database** -
This folder contains the migration files needed for our Wave application.

- **docs** -
This folder contains the documentation files. The files from which you are reading right now.

- **resources** -
This folder contains a few shared views used by Wave.

- **routes** -
This folder contains the routes defined for our wave application. You will see the web routes located at `wave/routes/web.php` and the API routes located at `/wave/routes/api.php`, go ahead and feel free to take a look at the defined routes.

- **src** -
This is where all the logic happens for our Wave application including Models, Controllers, Helpers, and much more.

- *composer.json* -
This file is used to require any dependencies required by Wave.
