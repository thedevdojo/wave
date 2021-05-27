<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'key' => 'browse_admin',
                'table_name' => NULL,
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            1 => 
            array (
                'key' => 'browse_bread',
                'table_name' => NULL,
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            2 => 
            array (
                'key' => 'browse_database',
                'table_name' => NULL,
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            3 => 
            array (
                'key' => 'browse_media',
                'table_name' => NULL,
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            4 => 
            array (
                'key' => 'browse_compass',
                'table_name' => NULL,
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            5 => 
            array (
                'key' => 'browse_menus',
                'table_name' => 'menus',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            6 => 
            array (
                'key' => 'read_menus',
                'table_name' => 'menus',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            7 => 
            array (
                'key' => 'edit_menus',
                'table_name' => 'menus',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            8 => 
            array (
                'key' => 'add_menus',
                'table_name' => 'menus',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            9 => 
            array (
                'key' => 'delete_menus',
                'table_name' => 'menus',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            10 => 
            array (
                'key' => 'browse_roles',
                'table_name' => 'roles',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            11 => 
            array (
                'key' => 'read_roles',
                'table_name' => 'roles',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            12 => 
            array (
                'key' => 'edit_roles',
                'table_name' => 'roles',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            13 => 
            array (
                'key' => 'add_roles',
                'table_name' => 'roles',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            14 => 
            array (
                'key' => 'delete_roles',
                'table_name' => 'roles',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            15 => 
            array (
                'key' => 'browse_users',
                'table_name' => 'users',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            16 => 
            array (
                'key' => 'read_users',
                'table_name' => 'users',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            17 => 
            array (
                'key' => 'edit_users',
                'table_name' => 'users',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            18 => 
            array (
                'key' => 'add_users',
                'table_name' => 'users',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            19 => 
            array (
                'key' => 'delete_users',
                'table_name' => 'users',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            20 => 
            array (
                'key' => 'browse_settings',
                'table_name' => 'settings',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            21 => 
            array (
                'key' => 'read_settings',
                'table_name' => 'settings',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            22 => 
            array (
                'key' => 'edit_settings',
                'table_name' => 'settings',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            23 => 
            array (
                'key' => 'add_settings',
                'table_name' => 'settings',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            24 => 
            array (
                'key' => 'delete_settings',
                'table_name' => 'settings',
                'created_at' => '2018-06-22 20:15:45',
                'updated_at' => '2018-06-22 20:15:45',
                'permission_group_id' => NULL,
            ),
            25 => 
            array (
                'key' => 'browse_categories',
                'table_name' => 'categories',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            26 => 
            array (
                'key' => 'read_categories',
                'table_name' => 'categories',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            27 => 
            array (
                'key' => 'edit_categories',
                'table_name' => 'categories',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            28 => 
            array (
                'key' => 'add_categories',
                'table_name' => 'categories',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            29 => 
            array (
                'key' => 'delete_categories',
                'table_name' => 'categories',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            30 => 
            array (
                'key' => 'browse_posts',
                'table_name' => 'posts',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            31 => 
            array (
                'key' => 'read_posts',
                'table_name' => 'posts',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            32 => 
            array (
                'key' => 'edit_posts',
                'table_name' => 'posts',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            33 => 
            array (
                'key' => 'add_posts',
                'table_name' => 'posts',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            34 => 
            array (
                'key' => 'delete_posts',
                'table_name' => 'posts',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            35 => 
            array (
                'key' => 'browse_pages',
                'table_name' => 'pages',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            36 => 
            array (
                'key' => 'read_pages',
                'table_name' => 'pages',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            37 => 
            array (
                'key' => 'edit_pages',
                'table_name' => 'pages',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            38 => 
            array (
                'key' => 'add_pages',
                'table_name' => 'pages',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            39 => 
            array (
                'key' => 'delete_pages',
                'table_name' => 'pages',
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            40 => 
            array (
                'key' => 'browse_hooks',
                'table_name' => NULL,
                'created_at' => '2018-06-22 20:15:46',
                'updated_at' => '2018-06-22 20:15:46',
                'permission_group_id' => NULL,
            ),
            41 => 
            array (
                'key' => 'browse_announcements',
                'table_name' => 'announcements',
                'created_at' => '2018-05-20 21:08:14',
                'updated_at' => '2018-05-20 21:08:14',
                'permission_group_id' => NULL,
            ),
            42 => 
            array (
                'key' => 'read_announcements',
                'table_name' => 'announcements',
                'created_at' => '2018-05-20 21:08:14',
                'updated_at' => '2018-05-20 21:08:14',
                'permission_group_id' => NULL,
            ),
            43 => 
            array (
                'key' => 'edit_announcements',
                'table_name' => 'announcements',
                'created_at' => '2018-05-20 21:08:14',
                'updated_at' => '2018-05-20 21:08:14',
                'permission_group_id' => NULL,
            ),
            44 => 
            array (
                'key' => 'add_announcements',
                'table_name' => 'announcements',
                'created_at' => '2018-05-20 21:08:14',
                'updated_at' => '2018-05-20 21:08:14',
                'permission_group_id' => NULL,
            ),
            45 => 
            array (
                'key' => 'delete_announcements',
                'table_name' => 'announcements',
                'created_at' => '2018-05-20 21:08:14',
                'updated_at' => '2018-05-20 21:08:14',
                'permission_group_id' => NULL,
            ),
            46 => 
            array (
                'key' => 'browse_themes',
                'table_name' => 'admin',
                'created_at' => '2017-11-21 16:31:00',
                'updated_at' => '2017-11-21 16:31:00',
                'permission_group_id' => NULL,
            ),
            47 => 
            array (
                'key' => 'browse_hooks',
                'table_name' => 'hooks',
                'created_at' => '2018-06-22 13:55:03',
                'updated_at' => '2018-06-22 13:55:03',
                'permission_group_id' => NULL,
            ),
            48 => 
            array (
                'key' => 'read_hooks',
                'table_name' => 'hooks',
                'created_at' => '2018-06-22 13:55:03',
                'updated_at' => '2018-06-22 13:55:03',
                'permission_group_id' => NULL,
            ),
            49 => 
            array (
                'key' => 'edit_hooks',
                'table_name' => 'hooks',
                'created_at' => '2018-06-22 13:55:03',
                'updated_at' => '2018-06-22 13:55:03',
                'permission_group_id' => NULL,
            ),
            50 => 
            array (
                'key' => 'add_hooks',
                'table_name' => 'hooks',
                'created_at' => '2018-06-22 13:55:03',
                'updated_at' => '2018-06-22 13:55:03',
                'permission_group_id' => NULL,
            ),
            51 => 
            array (
                'key' => 'delete_hooks',
                'table_name' => 'hooks',
                'created_at' => '2018-06-22 13:55:03',
                'updated_at' => '2018-06-22 13:55:03',
                'permission_group_id' => NULL,
            ),
            52 => 
            array (
                'key' => 'browse_plans',
                'table_name' => 'plans',
                'created_at' => '2018-07-03 04:50:28',
                'updated_at' => '2018-07-03 04:50:28',
                'permission_group_id' => NULL,
            ),
            53 => 
            array (
                'key' => 'read_plans',
                'table_name' => 'plans',
                'created_at' => '2018-07-03 04:50:28',
                'updated_at' => '2018-07-03 04:50:28',
                'permission_group_id' => NULL,
            ),
            54 => 
            array (
                'key' => 'edit_plans',
                'table_name' => 'plans',
                'created_at' => '2018-07-03 04:50:28',
                'updated_at' => '2018-07-03 04:50:28',
                'permission_group_id' => NULL,
            ),
            55 => 
            array (
                'key' => 'add_plans',
                'table_name' => 'plans',
                'created_at' => '2018-07-03 04:50:28',
                'updated_at' => '2018-07-03 04:50:28',
                'permission_group_id' => NULL,
            ),
            56 => 
            array (
                'key' => 'delete_plans',
                'table_name' => 'plans',
                'created_at' => '2018-07-03 04:50:28',
                'updated_at' => '2018-07-03 04:50:28',
                'permission_group_id' => NULL,
            ),
        ));
        
        
    }
}