<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnnouncementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('announcements')->delete();
        
        \DB::table('announcements')->insert(array (
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
        ),
    ));
        
        
    }
}