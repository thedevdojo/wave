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
                'guard_name' => 'admin',
                'name' => 'Admin User',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            1 =>
            array (
                'id' => 2,
                'guard_name' => 'trial',
                'name' => 'Free Trial',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ),
            2 =>
            array (
                'id' => 3,
                'guard_name' => 'basic',
                'name' => 'Basic Plan',
                'created_at' => '2018-07-03 05:03:21',
                'updated_at' => '2018-07-03 17:28:44',
            ),
            3 =>
            array (
                'id' => 4,
                'guard_name' => 'pro',
                'name' => 'Pro Plan',
                'created_at' => '2018-07-03 16:27:16',
                'updated_at' => '2018-07-03 17:28:38',
            ),
            4 =>
            array (
                'id' => 5,
                'guard_name' => 'premium',
                'name' => 'Premium Plan',
                'created_at' => '2018-07-03 16:28:42',
                'updated_at' => '2018-07-03 17:28:32',
            ),
            5 =>
            array (
                'id' => 6,
                'guard_name' => 'cancelled',
                'name' => 'Cancelled User',
                'created_at' => '2018-07-03 16:28:42',
                'updated_at' => '2018-07-03 17:28:32',
            ),
        ));


    }
}
