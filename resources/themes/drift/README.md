# Drift Theme

This is the Drift theme for the <a href="https://devdojo.com/wave" target="_blank">Wave, the SaaS Boilerplate</a>.

<img src="https://cdn.devdojo.com/images/september2024/drift-theme.jpeg" style="border 1px solid #dedee1; border-radius: 5px;" />

## Working with the Mega Menu

You will probably want to change items in the mega menu and therefore understanding how it works will be very important. It's actually quite simple. Let's cover the products menu. If you take a look at the navigation menu item for `products`, it will look something like this:

```html
<li><x-marketing.elements.nav-item dropdown="true" dropdown-ref="products">Products</x-marketing.elements.nav-item></li>
```

Here we are specifying that this `nav-item` is a `dropdown="true"` and we are saying that the `dropdown-ref` is `products`, this value will reference and open the element with an `x-ref="products"`, which looks something like this:

```html
<div 
    x-ref="products" 
    class="w-[628px] ..."
    ...>
        <div class="...">
            <h3>Title</h3>
            <div class="...">
                <x-marketing.elements.mega-menu-link icon="icon" title="Title" description="description"></x-marketing.elements.mega-menu-link>
                <x-marketing.elements.mega-menu-link icon="icon" title="Title" description="description"></x-marketing.elements.mega-menu-link>
                <x-marketing.elements.mega-menu-link icon="icon" title="Title" description="description"></x-marketing.elements.mega-menu-link>
            </div>
        </div>
        <div class="...">
            <h3>Title</h3>
            <div class="...">
                <x-marketing.elements.mega-menu-link icon="icon" title="Title" description="description"></x-marketing.elements.mega-menu-link>
                ...
            </div>
        </div>
    </div>
</div>
```

You'll notice that each of the individual dropdown items contains a **static width**, in this case `w-[628px]`. You will need to add the width that fits your content and this will be the width that is transitioned when the user hovers over this menu item.

> Determining the width of each section dynamically can result in inconsistencies because the content is hidden until the user hovers the menu item. Setting a static width is the most efficient way to get the desired result.

You may also use the `<x-marketing.elements.mega-menu-link>` element inside of each mega menu, or you can add your own custom elements items inside the mega menu.
