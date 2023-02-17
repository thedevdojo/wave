<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('roles')->delete();

        \DB::table('roles')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'admin',
                'display_name' => 'Admin User',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'trial',
                'display_name' => 'Free Trial',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'basic',
                'display_name' => 'Basic Plan',
                'created_at' => '2018-07-03 05:03:21',
                'updated_at' => '2018-07-03 17:28:44',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'pro',
                'display_name' => 'Pro Plan',
                'created_at' => '2018-07-03 16:27:16',
                'updated_at' => '2018-07-03 17:28:38',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'premium',
                'display_name' => 'Premium Plan',
                'created_at' => '2018-07-03 16:28:42',
                'updated_at' => '2018-07-03 17:28:32',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'cancelled',
                'display_name' => 'Cancelled User',
                'created_at' => '2018-07-03 16:28:42',
                'updated_at' => '2018-07-03 17:28:32',
            ),
        ));


    }
}
