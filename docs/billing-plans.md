# Retiring billing plans

Disabling a plan prevents new application checkout and Paddle plan switches to it. The server checks the current database record, the requested monthly/yearly cycle, and its configured price before contacting the payment provider. Existing subscriptions retain their plan and access.

Stripe still fulfills an already-paid checkout if its plan was retired after checkout opened. Paddle verification rejects unavailable plans or missing receipt prices and asks the customer to contact support if payment was taken; reconcile those payments manually.

Retiring a local plan does not archive provider prices or change the Stripe customer portal catalog. Manage those separately in the provider dashboard.
