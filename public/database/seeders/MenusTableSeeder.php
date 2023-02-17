<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'authenticated-menu',
                'created_at' => '2017-11-28 14:47:49',
                'updated_at' => '2018-04-13 22:25:28',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'guest-menu',
                'created_at' => '2018-04-13 22:25:37',
                'updated_at' => '2018-04-13 22:25:37',
            ),
        ));
        
        
    }
}