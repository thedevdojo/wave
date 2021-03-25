<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoutes()
    {
        $appURL = env('APP_URL');

        $urls = [
            '/',
            '/admin/login',
            '/@admin',
            '/blog',
            '/blog/category-1',
            '/blog/category-1/best-ways-to-market-your-application',
            '/docs/1.0/welcome',
            '/login',
            '/password/reset',
            '/register',
            '/p/about',
        ];

        echo  PHP_EOL;

        foreach ($urls as $url) {
            $response = $this->get($url);
            if((int)$response->status() !== 200){
                echo  $appURL . $url . ' (FAILED) did not return a 200.';
                $this->assertTrue(false);
            } else {
                echo $appURL . $url . ' (SUCCESS) 🍻';
                $this->assertTrue(true);
            }
            echo  PHP_EOL;
        }

    }

    public function testAuthRoutes()
    {
    	$user = \App\User::find(1);

    	$appURL = env('APP_URL');

        $urls = [
            '/',
            '/admin',
            '/@admin',
            '/blog',
            '/blog/category-1',
            '/blog/category-1/best-ways-to-market-your-application',
            '/docs/1.0/welcome',
            '/admin/announcements',
            '/admin/announcements/create',
            '/admin/announcements/1',
            '/admin/announcements/1/edit',
            '/admin/bread',
            '/admin/bread/users/create',
            '/admin/bread/users/edit',
            '/admin/categories',
            '/admin/categories/create',
            '/admin/categories/1',
            '/admin/categories/1/edit',
            '/admin/database',
            '/admin/hooks',
            '/admin/media',
            '/admin/menus',
            '/admin/menus/create',
            '/admin/menus/1',
            '/admin/menus/1/edit',
            '/admin/pages',
            '/admin/pages/create',
            '/admin/pages/1',
            '/admin/pages/1/edit',
            '/admin/plans',
            '/admin/plans/create',
            '/admin/plans/1',
            '/admin/plans/1/edit',
            '/admin/posts',
            '/admin/posts/create',
            '/admin/posts/5',
            '/admin/posts/5/edit',
            '/admin/profile',
            '/admin/roles',
            '/admin/roles/create',
            '/admin/roles/1',
            '/admin/roles/1/edit',
            '/admin/settings',
            '/admin/users',
            '/admin/users/create',
            '/admin/users/1',
            '/admin/users/1/edit',
            '/announcement/1',
            '/announcements',
            '/dashboard',
            '/notifications',
            '/settings/profile',
            '/settings/security',
            '/settings/api',
            '/settings/plans',
            '/settings/payment-information',
            '/settings/invoices'
        ];

        echo  PHP_EOL;

        foreach ($urls as $url) {
            $response = $this->actingAs($user)->get($url);
            if((int)$response->status() !== 200){
                echo  $appURL . $url . ' (FAILED) did not return a 200.';
                $this->assertTrue(false);
            } else {
                echo $appURL . $url . ' (SUCCESS) 🍻';
                $this->assertTrue(true);
            }
            echo  PHP_EOL;
        }
    }
}