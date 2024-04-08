<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => NULL,
                'order' => 1,
                'name' => 'Marketing',
                'slug' => 'marketing',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => NULL,
                'order' => 1,
                'name' => 'Tutorials',
                'slug' => 'tutorials',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
        ));
        
        
    }
}