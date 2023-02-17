<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('settings')->delete();

        \DB::table('settings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'key' => 'site.title',
                'display_name' => 'Site Title',
                'value' => 'Wave',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ),
            1 =>
            array (
                'id' => 2,
                'key' => 'site.description',
                'display_name' => 'Site Description',
                'value' => 'The Software as a Service Starter Kit built on Laravel & Voyager',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ),
            2 =>
            array (
                'id' => 4,
                'key' => 'site.google_analytics_tracking_id',
                'display_name' => 'Google Analytics Tracking ID',
                'value' => NULL,
                'details' => '',
                'type' => 'text',
                'order' => 4,
                'group' => 'Site',
            ),
            3 =>
            array (
                'id' => 6,
                'key' => 'admin.title',
                'display_name' => 'Admin Title',
                'value' => 'Wave',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            4 =>
            array (
                'id' => 7,
                'key' => 'admin.description',
                'display_name' => 'Admin Description',
                'value' => 'Create some waves and build your next great idea',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Admin',
            ),
            5 =>
            array (
                'id' => 8,
                'key' => 'admin.loader',
                'display_name' => 'Admin Loader',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Admin',
            ),
            6 =>
            array (
                'id' => 9,
                'key' => 'admin.icon_image',
                'display_name' => 'Admin Icon Image',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ),
            7 =>
            array (
                'id' => 10,
                'key' => 'admin.google_analytics_client_id',
            'display_name' => 'Google Analytics Client ID (used for admin dashboard)',
                'value' => '',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            8 =>
            array (
                'id' => 11,
                'key' => 'site.favicon',
                'display_name' => 'Favicon',
                'value' => '',
                'details' => NULL,
                'type' => 'image',
                'order' => 6,
                'group' => 'Site',
            ),
            9 =>
            array (
                'id' => 12,
                'key' => 'auth.dashboard_redirect',
                'display_name' => 'Homepage Redirect to Dashboard if Logged in',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 7,
                'group' => 'Auth',
            ),
            10 =>
            array (
                'id' => 13,
                'key' => 'auth.email_or_username',
                'display_name' => 'Users Login with Email or Username',
                'value' => 'email',
                'details' => '{
"default" : "email",
"options" : {
"email": "Email Address",
"username": "Username"
}
}',
                'type' => 'select_dropdown',
                'order' => 8,
                'group' => 'Auth',
            ),
            11 =>
            array (
                'id' => 14,
                'key' => 'auth.username_in_registration',
                'display_name' => 'Username when Registering',
                'value' => 'yes',
                'details' => '{
"default" : "yes",
"options" : {
"yes": "Yes, Include the Username Field when Registering",
"no": "No, Have it automatically generated"
}
}',
                'type' => 'select_dropdown',
                'order' => 9,
                'group' => 'Auth',
            ),
            12 =>
            array (
                'id' => 15,
                'key' => 'auth.verify_email',
                'display_name' => 'Verify Email during Sign Up',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 10,
                'group' => 'Auth',
            ),
            13 =>
            array (
                'id' => 16,
                'key' => 'billing.card_upfront',
                'display_name' => 'Require Credit Card Up Front',
                'value' => '1',
                'details' => '{
"on" : "Yes",
"off" : "No",
"checked" : false
}',
                'type' => 'checkbox',
                'order' => 11,
                'group' => 'Billing',
            ),
            14 =>
            array (
                'id' => 17,
                'key' => 'billing.trial_days',
                'display_name' => 'Trial Days when No Credit Card Up Front',
                'value' => '-1',
                'details' => NULL,
                'type' => 'text',
                'order' => 12,
                'group' => 'Billing',
            ),
        ));


    }
}
