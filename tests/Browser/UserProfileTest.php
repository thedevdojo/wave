<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserProfileTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testProfilePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', '=', 'scoobydoo@gmail.com')->first())
                ->visit('/@scoobydoo')
                ->assertSee('Scooby Doo');
        });
    }

}
