<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRegisterTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * @test
     *
     * @thows \Throwable
     */
    public function users_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'AlbertoRosas')
                    ->type('first_name', 'Alberto')
                    ->type('last_name', 'Rosas')
                    ->type('email', 'alberto@correo.com')
                    ->type('password', 'secret')
                    ->type('password_confirmation', 'secret')
                    ->press('@register-btn')
                    ->assertPathIs('/')
                    ->assertAuthenticated();
        });
    }

    /**
     * @test
     *
     * @thows \Throwable
     */
    public function users_cannot_register_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', '')
                ->press('@register-btn')
                ->assertPathIs('/register')
                ->assertPresent('@validation-errors');
        });
    }
}
