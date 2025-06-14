<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     */
    public function run(): void
    {

        DB::table('categories')->delete();

        DB::table('categories')->insert([
            0 => [
                'id' => 1,
                'parent_id' => null,
                'order' => 1,
                'name' => 'Marketing',
                'slug' => 'marketing',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ],
            1 => [
                'id' => 2,
                'parent_id' => null,
                'order' => 1,
                'name' => 'Tutorials',
                'slug' => 'tutorials',
                'created_at' => '2017-11-21 16:23:22',
                'updated_at' => '2017-11-21 16:23:22',
            ],
        ]);

    }
}
