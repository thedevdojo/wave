<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     */
    public function run(): void
    {
        \DB::table('permissions')->delete();
    }
}
