# Authentication

Wave is built on top of the default Laravel Authentication and includes a few additional features such as email verification, forgot password, username login, and of course billing upon registration.

- [Registration](/docs/{{version}}/features/authentication#register)
- [Login](/docs/{{version}}/features/authentication#login)
- [Email Verification](/docs/{{version}}/features/authentication#email-verification)
- [Login Options](/docs/{{version}}/features/authentication#login-options)
- [Forgot Password](/docs/{{version}}/features/authentication#forgot-password)

---

<a name="register"></a>
### Registration

Users can register for an account through your application visiting the `/register` route. The registration form has an input for username, but you can choose to hide this input by toggling *Username when Registering* in the **Auth** tab of your admin settings.

By default users will register and be on a 14 day trial. After 14 days the user will not be able to access the application; however they will still be able to visit parts of the app that allow them to submit payment information and change their settings.

You can choose to require a credit card upfront by visiting the admin settings `/admin/settings`, clicking on the Billing Tab, toggling *Require Credit Card Up Front*, and save.

![](/wave/img/docs/1.0/register-billing.png)

Now your users will be required to enter payment information for one of your plans before they can register.

> {warning} Before users can signup for a plan you will first need to configure billing and plans.

<a name="login"></a>
### Login

After a user has created an account through your application, they can login by visiting the `/login` route. After successfully logging in the user will then be redirected to their dashboard.

> {info} If you have just installed Wave you can login with the default email `admin@admin.com` and password as `password`

<a name="email-verification"></a>
### Email verification

You may choose to require your users to verify their email before signing up for a free trial. To enable this you will need to visit the admin settings page at `/admin/settings`, click on the Auth tab and then toggle *Verify Email during Sign Up*

![](/wave/img/docs/1.0/verify-email.png)

<a name="login-options"></a>
### Login with email or username

Optionally you may choose to allow users to login with their email address or their username. You can also change this in the **Auth** tab of your admin settings (see screenshot above).

<a name="forgot-password"></a>
### Forgot password

Users can click on the forgot password link on the login page and they will be taken to a form where they can enter their email address.The user will then receive an email with a link to your application where they can reset their password.

> {info} Quick note on Email Verification and Forgot Password, your application must be configured to send email before these features can be used.

The simplest way to test emails in development mode is to use [Mailtrap](https://mailtrap.io/). You can sign up for a free account and then enter your mailtrap credentials in your `.env` file:

```html
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
```