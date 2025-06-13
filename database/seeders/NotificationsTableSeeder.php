<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {

        \DB::table('notifications')->delete();

    }
}
