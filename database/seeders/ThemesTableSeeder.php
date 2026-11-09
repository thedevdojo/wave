<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SyncsIdSequences;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemesTableSeeder extends Seeder
{
    use SyncsIdSequences;

    /**
     * Auto generated seed file
     */
    public function run(): void
    {

        DB::table('themes')->delete();

        DB::table('themes')->insert([
            0 => [
                'id' => 1,
                'name' => 'Anchor Theme',
                'folder' => 'anchor',
                'active' => 1,
                'version' => 1.0,
            ],
        ]);

        $this->syncIdSequence('themes');
    }
}
