<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AuthTest extends DuskTestCase
{
    //use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();
       // $this->artisan('db:seed');
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testSignup()
    {
        $this->browse(function (Browser $browser) {

            User::where('email', '=', 'scoobydoo@gmail.com')->first()->delete();

            $browser->visit('/')
                ->clickLink('Sign Up')
                ->assertPathIs('/register')
                ->type('name', 'Scooby Doo')
                ->type('username', 'scoobydoo')
                ->type('email', 'scoobydoo@gmail.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->click('button[type="submit"]')
                ->waitForText('Thanks for signing up!')
                ->assertSee('Thanks for signing up!');
        });
    }

    public function testLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', '=', 'scoobydoo@gmail.com')->first())
                ->visit('/dashboard')
                ->mouseover('.user-icon')
                ->waitForText('Logout')
                ->clickLink('Logout')
                ->assertPathIs('/');
        });
    }

    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Login')
                ->assertPathIs('/login')
                ->type('email', 'scoobydoo@gmail.com')
                ->type('password', 'password123')
                ->click('button[type="submit"]')
                ->waitForText('Successfully logged in.')
                ->assertSee('Successfully logged in.');
        });
    }

}
