<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WaveKeyValuesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('wave_key_values')->delete();

        \DB::table('wave_key_values')->insert(array (
            0 =>
            array (
                'id' => 10,
                'type' => 'text_area',
                'keyvalue_id' => 1,
                'keyvalue_type' => 'users',
                'key' => 'about',
                'value' => 'Hello I am the admin user. You can update this information in the edit profile section. Hope you enjoy using Wave.',
            ),
        ));


    }
}
