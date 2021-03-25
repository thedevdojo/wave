# Billing

Wave comes packaged with integrated billing. This means that you can charge your customers to access your application or features in your application.

Wave uses [Stripe](https://stripe.com) and [Cashier](https://github.com/laravel/cashier).

- [Billing with Stripe](/docs/{{version}}/features/billing#stripe)

<a name="stripe"></a>
## Stripe

Setting up your app to work with Stripe is super simple. This is going to be the easiest way to configure your application to accept payments.

In this section we will show you how to setup a Stripe account and add your Stripe credentials to Wave. We will cover how to setup Products and Subscriptions in the next section.

### Create a Free Stripe Account

It's easy to setup a free Stripe account. Visit [https://stripe.com/register](https://stripe.com/register) and create an account.

After creating an account you'll be on your Stripe Dashboard. Then, to get your API keys you can click on the *Developers->API Keys*.

![](/wave/img/docs/1.0/stripe-dashboard.png)

### Adding Stripe API keys

> {warning} You can test out payments with your Test API keys, but you will need to activate your account before you can view your Live API keys. To toggle between *test* or *live* data, you'll need to toggle *Viewing Test Data* (see previous screenshot).

Now, that you have your Stripe API keys we will need to add them to your application `.env` file. The `.env` file is where we store all our application environment variables including our Stripe API Keys. Find and update your stripe keys in the following variables.

```php
STRIPE_MODE=test
STRIPE_TEST_KEY=pk_test_
STRIPE_TEST_SECRET=sk_test_
STRIPE_LIVE_KEY=pk_live_
STRIPE_LIVE_SECRET=sk_live_
```

Notice, when you are ready to go **live** with accepting real payments you will need to change `STRIPE_MODE` to `live`.

And That's it! You're ready to start accepting payments.

### Stripe Test Credit Cards

There are many Stripe credit card numbers that you can use for testing, which can be [found here](https://stripe.com/docs/testing). Here is an example card that you can use for testing purposes:

| Input | Value |
| : | : |
| CARD | 4242 4242 4242 4242 |
| EXP  | Any date in the future |
| CVC  | Any 3 digit code |
| ZIP  |  Any 5 digit zip code |

That's it for Stripe, next you may want to learn about subscriptions. Happy Billing ;)
