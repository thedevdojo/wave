<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('plans')->delete();

        \DB::table('plans')->insert([
            0 => [
                'id' => 1,
                'name' => 'Basic',
                'description' => 'Signup for the Basic User Plan to access all the basic features.',
                'features' => 'Basic Feature Example 1, Basic Feature Example 2, Basic Feature Example 3, Basic Feature Example 4',
                'role_id' => 3,
                'default' => 0,
                'monthly_price' => '5',
                'yearly_price' => '50',
                'created_at' => '2018-07-03 05:03:56',
                'updated_at' => '2018-07-03 17:17:24',
            ],
            1 => [
                'id' => 2,
                'name' => 'Premium',
                'description' => 'Signup for our premium plan to access all our Premium Features.',
                'features' => 'Premium Feature Example 1, Premium Feature Example 2, Premium Feature Example 3, Premium Feature Example 4',
                'role_id' => 4,
                'default' => 1,
                'monthly_price' => '8',
                'yearly_price' => '80',
                'created_at' => '2018-07-03 16:29:46',
                'updated_at' => '2018-07-03 17:17:08',
            ],
            2 => [
                'id' => 3,
                'name' => 'Pro',
                'description' => 'Gain access to our pro features with the pro plan.',
                'features' => 'Pro Feature Example 1, Pro Feature Example 2, Pro Feature Example 3, Pro Feature Example 4',
                'role_id' => 5,
                'default' => 0,
                'monthly_price' => '12',
                'yearly_price' => '120',
                'created_at' => '2018-07-03 16:30:43',
                'updated_at' => '2018-08-22 22:26:19',
            ],
        ]);

    }
}
