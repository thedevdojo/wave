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
        
        \DB::table('pages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'author_id' => 1,
                'title' => 'Hello World',
                'excerpt' => 'Hang the jib grog grog blossom grapple dance the hempen jig gangway pressgang bilge rat to go on account lugger. Nelsons folly gabion line draught scallywag fire ship gaff fluke fathom case shot. Sea Legs bilge rat sloop matey gabion long clothes run a shot across the bow Gold Road cog league.',
                'body' => '<p>Hello World. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>
<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>',
                'image' => 'pages/page1.jpg',
                'slug' => 'hello-world',
                'meta_description' => 'Yar Meta Description',
                'meta_keywords' => 'Keyword1, Keyword2',
                'status' => 'ACTIVE',
                'created_at' => '2017-11-21 16:23:23',
                'updated_at' => '2017-11-21 16:23:23',
            ),
            1 => 
            array (
                'id' => 2,
                'author_id' => 1,
                'title' => 'About',
                'excerpt' => 'This is the about page.',
                'body' => '<p>Wave is the ultimate&nbsp;Software as a Service Starter kit. If you\'ve ever wanted to create your own SAAS application, Wave can help save you hundreds of hours. Wave is one of a kind and it is built on top of Laravel and Voyager. Building your application is going to be funner&nbsp;than ever before... Funner may not be a real word, but you get where I\'m trying to go.</p>
<p>Wave has a bunch of functionality built-in that will save you a bunch of time. Your users will be able to update their settings, billing information, profile information and so much more. You will also be able to accept&nbsp;payments from your user with multiple vendors.</p>
<p>We want to help you build the SAAS of your dreams by making it easier and less time-consuming. Let\'s start creating some "Waves" and build out the SAAS in your particular niche... Sorry about that Wave pun...</p>',
                'image' => NULL,
                'slug' => 'about',
                'meta_description' => 'About Wave',
                'meta_keywords' => 'about, wave',
                'status' => 'ACTIVE',
                'created_at' => '2018-03-30 03:04:51',
                'updated_at' => '2018-03-30 03:04:51',
            ),
        ));
        
        
    }
}