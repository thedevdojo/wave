# Installation

- [Install Wave](#install-wave)
- [Create a New Repo](#create-repo)
- [Deploy to DigitalOcean](#deploy-to-digitalocean)
- [Logging into Your Application](#login)

---

<a name="install-wave"></a>
## Install Wave

Before installing wave you will need to make sure you have the minimum <a href="https://laravel.com/docs/deployment#server-requirements" target="_blank">server requirements</a> and then you'll want to clone the repo to your machine.

### Clone the Repo

You can clone the repo onto your local machine with the following command:

```
git clone https://github.com/thedevdojo/wave.git project_name
```

Change `project_name` with the name of your project. Optionally, you may want to create a [new Github Repo for your project](#create-repo).

---

Ok, now we can easily install Wave with these **4 simple steps**:

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


> For security measures you may want to regenerate your application key, be sure to run the following command below to secure your site before going live.

```php
php artisan key:generate
```

---

<a name="create-repo"></a>

## Create a New Repo

In most cases it's a good idea to create a separate repo for your new project. This will also make it easier to [deploy our application to DigitalOcean](#deploy-to-do).

If you are logged into your Github account, you can go to <a href="https://github.com/new" target="_blank">https://github.com/new</a> to create a new repository. Give it a name associated with your SAAS and click **Create Repository** (Keep this repo as public for now if you wish to deploy to DigitalOcean)

![create-repo.png](https://cdn.devdojo.com/images/march2021/create-repo.png)

After you click on create repo, you will need to open up the command line and `cd` into your `project_name` folder and run the following commands, replacing `tnylea/project_name` with the name of your repo:

```
rm -rf .git/
git init
git add --all
git commit -m "first commit for my SAAS"
git branch -M main
git remote add origin https://github.com/tnylea/project_name.git
git push -u origin main
```

If you refresh your Github Repository you should see that it now contains the files for your new SAAS application.

![repo-new.png](https://cdn.devdojo.com/images/march2021/repo-new.png)

---


<a name="deploy-to-digitalocean"></a>
## Deploy to Digital Ocean

![](https://cdn.devdojo.com/images/march2021/wave-on-do.png)

If you use Digital Ocean, you're in luck. You can easily start surfing the Wave(s) on DigitalOcean with our <a href="https://www.digitalocean.com/products/app-platform/" target="_blank">DO App Platform</a> integration. Not using DigitalOcean? <a href="https://m.do.co/c/dc19b9819d06" target="_blank">Get $100 in free DigitalOcean credit here</a>. 💵

Here are 3 simple steps to get started with your new SAAS on Digital Ocean.

1. [✏️ Update Repo Name](#update-repo-name)
2. [🚀 Deploy to DO](#deploy-to-do)
3. [🤓 Start Building Your SAAS](#start-building)

---

<a name="update-repo-name"></a>
### 1. ✏️ Update Repo Name

First, we need to change a few configuration files inside the `/.do` directory. You can edit these directly on github. Inside this folder you will see `app.yaml`, and `deploy.template.yaml`. Find and replace `thedevdojo/wave` with your repo name.

![github-update-name.png](https://cdn.devdojo.com/images/march2021/github-update-name.png)

Additionally, you'll need to edit the `Readme.md` and replace `thedevdojo/wave` with your repo name.

Do a search for all instances of `thedevdojo/wave` and replace it with the name of your repo in these 3 files and then we're ready to deploy your SAAS to DigitalOcean.

<a name="deploy-to-do"></a>
### 2. 🚀 Deploy to Digital Ocean

Ok, now you're ready to deploy your application to DigitalOcean. It's as simple as clicking the **Deploy to Do Button** inside your project repo Readme file.

Optionally, you can manually visit this URL, replacing `thedevdojo/wave` with your repo name.

`https://cloud.digitalocean.com/apps/new?repo=https://github.com/thedevdojo/wave/tree/main`

#### Follow the Steps in App Platform

Now, you can follow each of the setup steps in the DigitalOcean App Platform. Scroll to the bottom of each step and click the Next Button.

![do-step-1.png](https://cdn.devdojo.com/images/march2021/do-step-1.png)
![do-step-2.png](https://cdn.devdojo.com/images/march2021/do-step-2.png)

On the last step, you can choose the basic or the pro size. You can always change this later. Scroll down to the bottom and click 'Launch App'.

![do-step-3.png](https://cdn.devdojo.com/images/march2021/do-step-3.png)

Now, it may take a few minutes for your server to boot up with the Wave Script. You should see a loading screen similar to the following screenshot.

![do-step-4.png](https://cdn.devdojo.com/images/march2021/do-step-4.png)

and after it has completed you'll see a message similar to the following.

![do-final-step.png](https://cdn.devdojo.com/images/march2021/do-final-step.png)

You'll also notice that there is a link where you can visit to see your application live. Go ahead and click that URL and you'll see the following welcome Wave screen.

![wave-setup-1.png](https://cdn.devdojo.com/images/march2021/wave-setup-1.png)

Click on the continue button, and if everything went well, you'll see the following confirmation page.

![wave-setup-complete.png](https://cdn.devdojo.com/images/march2021/wave-setup-complete.png)

Finally, click on the Continue button and you'll see your new SAAS application in front of your eyes 🤯

![wave-application.png](https://cdn.devdojo.com/images/march2021/wave-application.png)

Boom! 💥 You now have a new SAAS application live on a server and your masterpeice is awaiting its creation.

<a name="start-building"></a>
### 3. 🤓 Setting Your Repo Private

Odds are that you probably want to keep your repo private. You can easily do that by visiting the settings tab of your repo and changing the privacy.

Screenshot here:

https://cloud.digitalocean.com/apps/github/install

---

Now, you're ready to start building your next great idea. If you want to learn more about setting up Wave and support the ongoing development of Wave you may want to consider becoming a <a href="https://devdojo.com/pro">DevDojo Pro</a> user. Together we can make this project even more awesome 😎


<a name="login"></a>
## Logging into your Application

After installing Wave you can login with the following default credentials:

- **email**: admin@admin.com
- **password**: password

After logging in you can feel free to change the admin email, username, and password by visiting the settings section in the user menu.
