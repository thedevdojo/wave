<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VoyagerThemesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('themes')->delete();

        \DB::table('themes')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Tailwind Theme',
                'folder' => 'tailwind',
                'active' => 1,
                'version' => '1.0',
                'created_at' => '2020-08-23 08:06:45',
                'updated_at' => '2020-08-23 08:06:45',
            )
        ));


    }
}
