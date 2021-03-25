# User Roles

Each user in your app will have a primary role and every role has permissions to allow or deny access to content. In this section we will talk about User Roles and how to utilize roles in your application.

This functionality is also documented in Voyager; although, it’s important to re-cap the functionality since it ties directly with a Subscription Plan.

> {primary} If you are unfamiliar with Voyager, I would recommend checking out the documentation at https://laravelvoyager.com/docs

- [User Roles](#user-roles)
    - [🍞 The BREAD System](#%f0%9f%8d%9e-the-bread-system)
    - [Adding Roles & Permissions](#adding-roles--permissions)
    - [Checking Permissions](#checking-permissions)

---

<a name="bread"></a>
### 🍞 The BREAD System

Wave uses the BREAD (browse, read, edit, add, and delete) system as opposed to the CRUD (create, read, update, and delete) system.

By using the BREAD system we can specify what type of permissions the User Role has in relation to any content on your site. The user role can have permissions to either Browse, Read, Edit, Add, or Delete any data on your website.

> {info} Here's a quick example: If we were to create a new role called **editor** and then assign the **editor** permissions to **Browse**, **Read**, and **Add** Posts, they will only have permission to those particular actions. Meaning that user can only Browse posts, Read posts, and Add posts. The user will not have permissions to **Edit** or **Delete** posts. Pretty straight forward, right?

Since each Subscription Plan is associated with a Role. We can say that each plan has a specific set of permissions to Browse, Read, Edit, Add, or Delete content within your application.

<a name="roles-permissions"></a>
### Adding Roles & Permissions

In your application you can view or add Roles in your application by visiting `/admin/roles`. To add a new role you can click on the `Add New` button:

![](/wave/img/docs/1.0/user-roles-1.png)

You can give the role a name and then specify the BREAD permissions for all the content on your site.

![](/wave/img/docs/1.0/user-roles-2.png)

<a name="checking-permissions"></a>
### Checking Permissions

Next if you would like to check for permissions you can use a few helpers. Here is how you can check user permissions from a user object.

```php
// via user object
$canViewPost = $user->can('read', $post);
$canViewPost = Auth::user()->can('read', $post);

// via controller
$canViewPost = $this->authorize('read', $post);
```

You may also choose to use the Voyager facade and pass the permission as a string:

```php
$canBrowsePost = Voyager::can('browse_posts');
$canViewPost = Voyager::can('read_posts');
$canEditPost = Voyager::can('edit_posts');
$canAddPost = Voyager::can('add_posts');
$canDeletePost = Voyager::can('delete_posts');
```

Lastly, you can use the blade syntax inside of your views to check whether a user can edit a particular post, like `@if` so:

```html
```

---

You may also pass the Model to check for permissions:

```html
\@can('browse', new TCG\Voyager\Models\Post)
    
    I can browse posts!

@endcan
```

Be sure to note that the permission will be tied to the Model specified in the Voyager Bread section.

![](/wave/img/docs/1.0/user-roles-3.png)

You can then choose to change the location of your model. For instance, you may want to create a new model inside of your app folder, and swap out the namespace with `App\Post`.

---

Ok, next let's move on to talking about notifications.
