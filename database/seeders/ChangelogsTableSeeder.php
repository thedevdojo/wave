<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChangelogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('changelogs')->delete();

        \DB::table('changelogs')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'Wave 1.0 Released',
                'description' => 'We have just released the first official version of Wave. Click here to learn more!',
                'body' => '<p>It\'s been a fun Journey creating this awesome SAAS starter kit and we are super excited to use it in many of our future projects. There are just so many features that Wave has that will make building the SAAS of your dreams easier than ever before.</p>
<p>Make sure to stay up-to-date on our latest releases as we will be releasing many more features down the road :)</p>
<p>Thanks! Talk to you soon.</p>',
            'created_at' => '2018-05-20 23:19:00',
            'updated_at' => '2018-05-21 00:38:02',
        )));

        \DB::table('changelogs')->insert(array (
            1 =>
            array (
                'id' => 2,
                'title' => 'Wave 2.0 Released',
                'description' => 'Wave V2 has been released with some awesome new features. Be sure to read up on what\'s new!',
                'body' => '<p>This new version of Wave includes the following updates:</p><ul><li>Update to the latest version of Laravel</li><li>New Payment integration with Paddle</li><li>Rewritten theme support</li><li>Deployment integration</li><li>Much more awesomeness</li></ul><p>Be sure to check out the official Wave v2 page at <a href="https://devdojo.com/wave">https://devdojo.com/wave</a></p>',
                'created_at' => '2020-03-20 23:19:00',
                'updated_at' => '2020-03-21 00:38:02',
        )));

        \DB::table('changelogs')->insert(array (
            1 =>
            array (
                'id' => 3,
                'title' => 'Wave 3.0 Released',
                'description' => 'Version 3 has been released with some major updates. Click here to find out what\'s new!',
                'body' => '<p>Wave V3 has some awesome and significant changes. Below is an overview of all the things that have changed.</p><blockquote>BTW, this is the changelog where you can add/edit entries to explain to your users what\'s new in your product. <a href="/admin/changelogs/3/edit">Click here to change this changelog entry</a></blockquote><p>In this new version of Wave we are no longer using <a href="https://github.com/thedevdojo/voyager" target="_blank"><span style="text-decoration: underline;">Voyager</span></a> for our admin panel. Instead we are leveraging <a href="https://filamentphp.com" target="_blank"><span style="text-decoration: underline;">FilamentPHP</span></a> which will give us so many things out of the box like a Form Builder, Table Builder, Notifications, and so much more.</p><p>We\'re also using an <a href="https://devdojo.com/auth" target="_blank"><span style="text-decoration: underline;">Authenticaiton package</span></a> that will allow you to configure your authentication in one place and have it stay the same no matter which theme you use.</p><p>We have also decided to go all-in on the <a href="https://tallstack.dev" target="_blank"><span style="text-decoration: underline;">Tall Stack</span></a>, this means that Livewire components can be used in any theme and anywhere on the site and it will just work 👌</p><p>There are so many additional things that have been included in the latest version. Be sure to check out the <a href="https://devdojo.com/wave" target="_blank">Wave page</a> here to learn more ✨</p>',
                'created_at' => '2024-08-01 12:00:00',
                'updated_at' => '2024-08-01 12:00:00',
        )));


    }
}
