<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('pages')->delete();

        \DB::table('pages')->insert([
            0 => [
                'id' => 1,
                'author_id' => 1,
                'title' => 'Example Page',
                'excerpt' => 'This is an example page. Create a page in the Wave admin and have it show up on the site.',
                'body' => '<p>This is an example page to showcase how a simple page can be created. You\'ll notice that this page also routes to a URL on your website. In this case the URL is mapped to `/example-page`. You can create as many pages as you would like.</p><h3>Creating Pages</h3><p>To create a new page you can simply visit the admin section at `/admin/pages`. You can then create a new page and add content. Here are some advantages of creating the page inside the admin.</p><ul><li>Automatically routes to a URL</li><li>Simple to create new pages</li><li>Simple to edit page</li><li>Many more</li></ul><p>You can feel free to create a page via the admin or you can create the page by adding it to your themes pages directory. The choice is yours.</p><h3>Quick Warning</h3><p>If you create a page inside the admin that has a slug of `about` and then you create a page inside your theme directory at `/pages/about/index.blade.php`. The two pages will conflict and you\'ll only see it from your themes page directory. Just make sure you only create the page in one location.</p>',
                'image' => null,
                'slug' => 'example-page',
                'meta_description' => 'This is a simple meta description for SEO purposes',
                'meta_keywords' => 'keyword1, keyword2',
                'status' => 'ACTIVE',
                'created_at' => '2017-11-21 16:23:23',
                'updated_at' => '2017-11-21 16:23:23',
            ],
            1 => [
                'id' => 2,
                'author_id' => 1,
                'title' => 'About',
                'excerpt' => 'Learn more about Wave. This is an example about page.',
                'body' => '<p>Wave is an all-in-one Software as a Service (SaaS) starter kit designed to give developers a head start in building their next big idea. Packed with essential features, Wave provides a smooth and powerful development experience, helping you skip the repetitive tasks and focus on what really matters: your unique application.</p><h3><strong>Why Choose Wave?</strong></h3><p>Wave offers an extensive toolkit to transform your application from an idea to a fully-fledged SaaS product. With Wave, developers can:</p><ul><li><strong>Jumpstart their SaaS application:</strong> Begin with built-in features like user management, authentication, and billing, so you don\'t have to reinvent the wheel.</li><li><strong>Fully customize:</strong> Tailor every aspect of your app, from themes to user roles, to match your brand\'s needs.</li><li><strong>Enjoy modern design:</strong> Wave is built using the TALL stack (Tailwind, Alpine, Laravel, Livewire), offering a sleek and responsive interface.</li><li><strong>Deploy with ease:</strong> Equipped with powerful tools, Wave simplifies the deployment process to get your application up and running quickly.</li></ul><p><br></p><h3><strong>Packed with Powerful Features</strong></h3><p>Wave isn\'t just a framework; it\'s a complete package that includes everything you need to launch a subscription-based application. Some of its standout features are:</p><ul><li><strong>User Management:</strong> Built-in user registration, authentication, and profile management, all customizable to fit your app\'s requirements.</li><li><strong>Subscription Billing:</strong> Integrated with Stripe and Paddle, Wave makes it easy to manage subscriptions, handle payments, and create invoices.</li><li><strong>Themes and Templates:</strong> Choose from beautifully designed themes, or create your own. Easily switch between themes using Wave\'s built-in theming engine.</li><li><strong>Admin Interface:</strong> Powered by FilamentPHP, Wave includes a robust admin panel to manage users, roles, and app settings efficiently.</li></ul><p><br></p><h3><strong>Start Building with Wave Today</strong></h3><p>Wave is more than just a SaaS starter kit; it\'s a robust platform designed to handle your application\'s future growth. Whether you\'re building an MVP, launching a new SaaS product, or scaling your existing platform, Wave equips you with the tools and flexibility to succeed.</p><p><strong>Key Benefits Recap</strong></p><ul><li><strong>Save Time:</strong> Skip the groundwork and start building right away with Wave\'s ready-to-use features.</li><li><strong>Scale with Confidence:</strong> Wave\'s modularity and customization options make it easy to evolve as your business grows.</li><li><strong>Optimized for Developers:</strong> Enjoy a developer-friendly experience with modern tools and a straightforward workflow.</li></ul><p>Ready to take your next SaaS project to the next level? Let Wave be your guide.</p><p>This structure provides a comprehensive overview of Wave while highlighting its key features and benefits. Feel free to tweak or expand on any sections to suit your needs!</p>',
                'image' => null,
                'slug' => 'about',
                'meta_description' => 'About Wave',
                'meta_keywords' => 'about, wave',
                'status' => 'ACTIVE',
                'created_at' => '2018-03-30 03:04:51',
                'updated_at' => '2018-03-30 03:04:51',
            ],
        ]);

    }
}
