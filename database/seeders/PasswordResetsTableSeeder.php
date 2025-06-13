<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PasswordResetsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {

        \DB::table('password_resets')->delete();

    }
}
