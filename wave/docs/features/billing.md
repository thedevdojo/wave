# Billing

Wave comes packaged with integrated billing. This means that you can charge your customers to access your application or access specific features in your application.

Wave integrates seamlessly with <a href="https://paddle.com" target="_blank">Paddle</a> and <a href="https://stripe.com" target="_blank">Stripe</a>. There are a few things to consider when choosing the right Payment Provider for your application. **Main differences:** 👉 Paddle has less options making it less confusing. It also supports Paypal. Stripe is rock-solid, it has a better API, and a better sandbox experience. It's really up to you as to which provider you would like to use 😉

- [Paddle Integration](#paddle-integration)
- [Stripe Integration](#stripe-integration)
- [Accepting Real Payments](#accept-real-payments)
- [Testing the Billing Process](#test-billing-process)
- [Handle Webhooks](#handle-webhooks)

<a name="paddle-integration"></a>
## Paddle Integration

In this quick section we'll show you how to create a new paddle account add add the Paddle API Credentials to your application.

### Create a Paddle Account

In order to integrate your application with Paddle you will need to signup for an account at <a href="https://paddle.com/signup" target="_blank">paddle.com/signup</a>. It may take a few days to get access to your Paddle account before you're ready to go live. In the meantime, you can signup for a Sandbox account at <a href="https://sandbox-vendors.paddle.com/signup" target="_blank">sandbox-vendors.paddle.com/signup</a>, and start testing out your payment functionality right away.

After you have created your Paddle Account you'll be able to login and see your dashboard, which should look similar to the following:

![paddle-dashboard.png](https://cdn.devdojo.com/images/april2021/paddle-dashboard.png)

Next, let's add your Paddle API credentials.

### Add the Paddle API Credentials

Inside of your Paddle Dashboard you'll see a button under the **Developer Tools** menu, called **Authentication**, click on that button to get your API Authentication Credentials.

![paddle-authentication.png](https://cdn.devdojo.com/images/april2021/paddle-authentication.png)

On this page you'll find your **Vendor ID** and your **API Auth Code**. These are the credentials that you will need to add to your `.env` file for `PADDLE_VENDOR_ID` and `PADDLE_VENDOR_AUTH_CODE`:

```
PADDLE_VENDOR_ID=9999
PADDLE_VENDOR_AUTH_CODE=YOUR_REALLY_LONG_API_KEY_HERE
PADDLE_ENV=sandbox
```

After adding these credentials, your application has been successfully configured with Paddle.

<a name="stripe-integration"></a>
## Stripe Integration

Activate your Stripe integration via *CASHIER_VENDOR* variable in the `.env` file:

`CASHIER_VENDOR="stripe"`

```
## NEW STRIPE INTEGRATION
CASHIER_VENDOR="paddle"
#CASHIER_VENDOR="stripe"

CASHIER_STRIPE_CALCULATE_TAXES=FALSE
CASHIER_STRIPE_ALLOW_PROMO_CODES=TRUE

STRIPE_KEY="PUBLIC KEY"
STRIPE_SECRET="PRIVATE KEY"
## END NEW STRIPE INTEGRATION
```

Add your Public and Private key.

> Notice: In the next section when we show you how to add subscription plans, you'll need to change your **plan_id** in the *plans* for your corresponding Stripe Price ID

<a name="accept-real-payments"></a>
## Accepting Real Payments

When you are ready to start accepting real payments for your application you will need to change the `PADDLE_ENV` from `sandbox` to `live`. If you are using Stripe it will detect if you are using sandbox mode based on the API Key and Secret. Now you're ready to accept live payments 💵

<a name="test-billing-process"></a>
## Testing the Billing Process

Before you can test out the full billing process, you will need to add a few [Subscription Plans](/docs/features/subscription-plans).

**Note**: If you're using Paddle Sandbox mode, you will need to test your app from a `http://localhost` URL. The best way to do this is to utilize the laravel **Artisan Serve** command.

After adding subscription plans and configuring your application with Paddle or Stripe, you will now be able to test out the billing process using the following credentials:

```
Credit Card: 4242 4242 4242 4242
Expiration: Any Future Date
CVC: Any 3 digit code
```

<a name="handling-webhooks"></a>
## Handling Webhooks

If you are using the Paddle Payment provider, the webhook is already configured; however, if you are using Stripe you will need to add the following URL to your `routes/web.php` in order to listen Cancel or Updates in the client account.

`YOUR_URL/wave-stripe/webhook`

---

After connecting your Payment Provider, you'll want to configure your app with a few [Subscription Plans](/docs/features/subscription-plans) in order to test out the whole process. Let's do this in the [next step](/docs/features/subscription-plans).

---
