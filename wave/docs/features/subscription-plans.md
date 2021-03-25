# Subscription Plans
Billing users for a subscription plan is pretty straight forward. Every Plan hasOne Role, so when a user subscribes to a plan they will inherit the permissions associated with that user role.

The Plan you create in Wave will reference a plan you create in Stripe or BrainTree.

- [Current Plans](/docs/1.0/features/subscription-plans#current-plans)
- [Deleting Plans](/docs/1.0/features/subscription-plans#delete-plans)
- [Creating Plans](/docs/1.0/features/subscription-plans#create-plans)
- [Creating Plans in Stripe](/docs/1.0/features/subscription-plans#create-plans-stripe)

---

<a name="current-plans"></a>
### Current Plans

When you install Wave you will see there are 3 default plans:

1. Basic 
2. Premium 
3. Standard 

You can delete these plans and create your own if you would like or you can edit the current plans.

<a name="delete-plans"></a>
### Deleting Plans

In order to show you how to create a new plan, we will delete the existing **Standard** plan. To delete plans you can visit `/admin/plans` and click Delete:

![](/wave/img/docs/1.0/wave-plans-delete.png)

Since our plan is associated with a role, we will also delete the associated role at `/admin/roles`

![](/wave/img/docs/1.0/wave-roles-delete.png)

We will cover more about User Roles in the next section.

<a name="create-plans"></a>
### Creating Plans

Now, let’s create a new plan called *starter*. But before I create a new plan I will first create a new role that I want to assign to this plan. My new role will be called *starter* as well, but you can give the role any name you would like. To create a new role click on the `Add New` button.

![](/wave/img/docs/1.0/wave-role-add-new.png)

Then we can create our new role called **starter**

![](/wave/img/docs/1.0/wave-role-create.png)

Notice on this page you can specify permissions for this role. We will talk more about this in the next section. For now, you can choose to check a few of them, or leave them all unchecked. Below is a screenshot of what I have checked in this example:

![](/wave/img/docs/1.0/wave-role-permissions.png)

Now that the role is created we can create a new plan and associate it with a role:

![](/wave/img/docs/1.0/wave-plan-new.png)

> {primary} Notice the **Plan ID** when creating your plan. This Plan ID is an ID we need to create in Stripe or BrainTree. We'll do this in the next step.

Fill out the rest of the info on the plan and click `Save` to create your new plan.

<a name="create-plans-stripe"></a>
### Creating Plans in Stripe

To create a new plan in Stripe, login to your dashboard and click **Products**->**Create Product**

![](/wave/img/docs/1.0/plans-stripe-dashboard.png)

Next, you'll give the plan a name and click Create Product

![](/wave/img/docs/1.0/plans-stripe-create.png)

Next, you'll be taken to the Product Pricing page. This is where you can specify the **ID** that we reference in our plan for our site.

![](/wave/img/docs/1.0/plans-stripe-new.png)

After you fill out the necessary info, click on Add Pricing Plan to finish creating your plan. And now your users can signup for your **starter** plan.

---

Next, we'll talk about User Roles. Remember every Plan is associated with a User Role, and this is how we will determine what a user has access to in your application.