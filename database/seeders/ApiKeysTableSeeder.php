<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ApiKeysTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     */
    public function run(): void
    {

        \DB::table('api_keys')->delete();

    }
}
