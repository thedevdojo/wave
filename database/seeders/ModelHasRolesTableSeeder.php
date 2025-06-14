<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     */
    public function run(): void
    {

        DB::table('model_has_roles')->delete();

        DB::table('model_has_roles')->insert([
            0 => [
                'role_id' => 1,
                'model_type' => 'users',
                'model_id' => 1,
            ],
        ]);

    }
}
