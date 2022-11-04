# Configurations

Wave has many configs to help you customize and build your SAAS app!

Wave is also built on top of Voyager, so all the Voyager configs are available to you as well. To learn more about those you can check out the <a href="https://voyager-docs.devdojo.com/getting-started/configurations" target="_blank">voyager docs here</a>.

---

- [Settings](#settings)
- [Configs](#configs)

<a name="settings"></a>
## Wave Settings Configuration

There are many settings available in your Wave admin section. Visit `/admin/settings` and you will be at your application settings. Which has the following settings you can modify.

> In order to login to the admin section of your application you can use the following credentials `admin@admin.com` and password as `password`

### Site Settings

From the Site Settings tab you can modify the following settings:
- **Site Title** - The title of your application
- **Site Description** - The description of your application
- **Google Analytics Tracking ID** - Enter your Analytics Tracking code to track page views and analytics
- **Favicon** - Upload your application favicon

### Admin Settings

These are settings to customize your admin
- **Admin Title** - The title of you application admin
- **Google Analytics Client ID** - This is used to show Google Analytics in your admin dashboard
- **Admin Description** - The description of your application admin
- **Admin Loader** - The loading image for your admin
- **Admin Icon Image** - The admin or application icon image

### Authentication Settings

- **Homepage Redirect to Dashboard if Logged in** - When an authenticated user visits the homepage you may with to redirect them to the application dashboard
- **Users Login with Email or Username** - Choose whether you want your users to login with their email or username
- **Username when Registering** - Show the username in the signup form or have it automatically generated based on the users name
- **Verify Email during Sign Up** - Enable this setting if you want your users to verify their email before being able to login

### Billing

- **Require Credit Card Up Front** - You can choose whether or not you want users to enter a credit card while signing up
- **Trial Days when No Credit Card Up Front** - Specify the amount of total trial days. Specify `-1` if you don't want to have any trial days.

---

<a name="configs"></a>
## Wave Configuration File

There are a few logical configurations you can make within the wave configuration file located at `/config/wave.php`. The contents of that file is as follows:

```php
<?php

return [

	'profile_fields' => [
		'about'
	],

	'api' => [
		'auth_token_expires' 	=> 60,
		'key_token_expires'		=> 1,
	],

	'auth' => [
		'min_password_length' => 5,
	],

	'user_model' => App\Models\User::class,
	'show_docs' => env('WAVE_DOCS', true),
    'demo' => env('WAVE_DEMO', false),
    'dev_bar' => env('WAVE_BAR', false),

    'paddle' => [
        'vendor' => env('PADDLE_VENDOR_ID', ''),
        'auth_code' => env('PADDLE_VENDOR_AUTH_CODE', ''),
        'env' => env('PADDLE_ENV', 'sandbox')
    ]

];
```

- **profile_fields** - Whenever you want to dynamically create a new user profile field such as `about`, `social_links`, or any other field you will need to include the field name in this config. You will learn all about *Custom Profile Fields* in the [User Profiles Section](/docs/features/user-profiles)

- **api => auth_token_expires** - This is the amount of time you want your JSON web token to expire. After this token has expired the app will then request a refresh token. You will most likely never need to change this value, but it's there if you need it.

- **api => key_token_expires** - This is the amount of time (in minutes) an API token will expire when it is generated with a user API Key.

    A user can generate an API key in your application which is used to create an API token during a request. After the default **1** minute. The user or the users application will need to make another request with their API key.

- **auth => min_password_length** - This is the minimum password length a user must enter when registering for an acccount.

- **user_model** - This is the default user model of your application. In most cases this is going to be the `App\Models\User` model. If you are using a different user model you will want to add that here.

    Remember your User Model will also need to extend the `\Wave\User` model. If you take a look at the `App\Models\User` model you can see it extends from the Wave user model:

    ```php
    class User extends \Wave\User
    ```

- **show_docs** - When developing your application you may want to keep the documentation accessible in your app. Set this to false if you do not want the docs to be accessible.

- **demo** - This is primarily used for demo purposes of the Wave project, in your project you will probably want to set this to false (unless you want to test out some of the demo functionality).

- **dev_bar** - If you enable the **dev_bar**, you will have a developer bar at the bottom of your screen that will allow you to easily jump to the documentation or the admin.

- **paddle** - This is the configuration settings you will need to integrate billing into your application. You can learn more about these configurations from the [billing documentation](/docs/features/billing).

    ---

    > Wave themes will give you unlimited configurations for your SAAS. We'll cover more of this later, or you can jump to the [Themes section by clicking here](/docs/features/themes).
