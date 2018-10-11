<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCanLoginTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function registered_users_can_login()
    {

        // Variables
        $email = 'alberto@test.com';

        factory(User::class)->create(['email' => $email]);

        $this->browse(function (Browser $browser) use ($email) {
            $browser->visit('login')
                    ->type('email', $email)
                    ->type('password', 'secret')
                    ->press('@login-btn')
                    ->assertPathIs('/')
                    ->assertAuthenticated();
        });
    }

    /**
     * @test
     *
     * @thows \Throwable
     */
    public function users_cannot_login_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', '')
                ->press('@login-btn')
                ->assertPathIs('/login')
                ->assertPresent('@validation-errors');
        });
    }
}
