<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Wave\Facades\Wave;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fix the database structure first
        $this->fixDatabaseStructure();
        
        $this->call([
            ApiKeysTableSeeder::class,
            CategoriesTableSeeder::class,
            PasswordResetsTableSeeder::class,
            PermissionsTableSeeder::class,
            PermissionRoleTableSeeder::class,
            ModelHasRolesTableSeeder::class,
            PlansTableSeeder::class,
            PostsTableSeeder::class,
            SettingsTableSeeder::class,
            ProfileKeyValuesTableSeeder::class,
            ThemesTableSeeder::class,
            InspirationTagSeeder::class,
            InspirationSeeder::class,
        ]);
    }
    
    /**
     * Fix the database structure
     */
    protected function fixDatabaseStructure()
    {
        // Only perform minimal fixing - the migrations should handle most of this
        
        // Make sure user_settings has workspace_id
        if (Schema::hasTable('user_settings') && !Schema::hasColumn('user_settings', 'workspace_id')) {
            DB::statement("ALTER TABLE user_settings ADD COLUMN workspace_id BIGINT UNSIGNED NULL AFTER user_id");
            
            // Add foreign key if workspaces table exists
            if (Schema::hasTable('workspaces')) {
                try {
                    DB::statement("ALTER TABLE user_settings ADD CONSTRAINT user_settings_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES workspaces(id) ON DELETE CASCADE");
                } catch (\Exception $e) {
                    // Foreign key might already exist or have other issues
                    \Illuminate\Support\Facades\Log::info('Could not add foreign key: ' . $e->getMessage());
                }
            }
        }
        
        // Make sure generated_posts has workspace_id if it exists
        if (Schema::hasTable('generated_posts') && !Schema::hasColumn('generated_posts', 'workspace_id')) {
            DB::statement("ALTER TABLE generated_posts ADD COLUMN workspace_id BIGINT UNSIGNED NULL AFTER user_id");
            
            // Add foreign key if workspaces table exists
            if (Schema::hasTable('workspaces')) {
                try {
                    DB::statement("ALTER TABLE generated_posts ADD CONSTRAINT generated_posts_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES workspaces(id) ON DELETE CASCADE");
                } catch (\Exception $e) {
                    // Foreign key might already exist or have other issues
                    \Illuminate\Support\Facades\Log::info('Could not add foreign key to generated_posts: ' . $e->getMessage());
                }
            }
        }
    }
}

if (!function_exists('fixPostgresSequence')) {

    function fixPostgresSequence()
    {
        if (config('database.default') === 'pgsql') {
            $tables = \DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\' ORDER BY table_name;');
            foreach ($tables as $table) {
                if (\Schema::hasColumn($table->table_name, 'id')) {
                    $columnType = \DB::select("SELECT data_type FROM information_schema.columns WHERE table_name = '{$table->table_name}' AND column_name = 'id'")[0]->data_type;
                    // Only proceed if the 'id' column is numeric
                    if (in_array($columnType, ['integer', 'bigint', 'smallint', 'smallserial', 'serial', 'bigserial'])) {
                        $seq = \DB::table($table->table_name)->max('id') + 1;
                        \DB::select('SELECT setval(pg_get_serial_sequence(\'' . $table->table_name . '\', \'id\'), coalesce(' . $seq . ',1), false) FROM ' . $table->table_name);
                    }
                }
            }
        }
    }
}

