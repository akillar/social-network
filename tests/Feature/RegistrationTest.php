<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;

    /** 
     * @test
     * @throws \Throwable
     */
    public function users_can_register() {

        $this->get(route('register'))->assertSuccessful();

        // Given
        $response = $this->post(route('register'), $this->userValidData());

        // When
        $response->assertRedirect('/');
        
        // Then
        $this->assertDatabaseHas('users', [
            'name' => 'AlbertoRosas2',
            'first_name' => 'Alberto',
            'last_name' => 'Rosas',
            'email' => 'alberto@email.com',
        ]);

        $this->assertTrue(
            Hash::check('secret', User::first()->password),
            'The Password needs to be Hashed.'
        );
        
        
    }
    
    /** 
     * @test
     * @throws \Throwable
     */
    public function the_name_is_required() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['name' => null])

        )->assertSessionHasErrors('name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_name_must_be_a_string() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['name' => 1234])

        )->assertSessionHasErrors('name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_name_may_not_be_greater_than_60_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['name' => str_random(61)])

        )->assertSessionHasErrors('name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_name_must_be_at_least_3_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['name' => str_random(2)])

        )->assertSessionHasErrors('name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_name_must_be_unique() {

        // Variables
        factory(User::class)->create(['name' => 'AlbertoRosas']);

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['name' => 'AlbertoRosas'])

        )->assertSessionHasErrors('name');
        
    }
    
    /** 
     * @test
     * @throws \Throwable
     */
    public function the_name_may_only_contain_letters_and_numbers() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['name' => 'Alberto Rosas5'])

        )->assertSessionHasErrors('name');
        
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_first_name_is_required() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['first_name' => null])

        )->assertSessionHasErrors('first_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_first_name_must_be_a_string() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['first_name' => 1234])

        )->assertSessionHasErrors('first_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_first_name_may_not_be_greater_than_60_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['first_name' => str_random(61)])

        )->assertSessionHasErrors('first_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_first_name_must_be_at_least_3_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['first_name' => str_random(2)])

        )->assertSessionHasErrors('first_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_first_name_may_only_contain_letters() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['first_name' => 'Alberto Rosas5'])

        )->assertSessionHasErrors('first_name');

    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_last_name_is_required() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['last_name' => null])

        )->assertSessionHasErrors('last_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_last_name_must_be_a_string() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['last_name' => 1234])

        )->assertSessionHasErrors('last_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_last_name_may_not_be_greater_than_60_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['last_name' => str_random(61)])

        )->assertSessionHasErrors('last_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_last_name_must_be_at_least_3_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['last_name' => str_random(2)])

        )->assertSessionHasErrors('last_name');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_last_name_may_only_contain_letters() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['last_name' => 'Alberto Rosas5'])

        )->assertSessionHasErrors('last_name');

    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_email_is_required() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['email' => null])

        )->assertSessionHasErrors('email');
    }
    
    /** 
     * @test
     * @throws \Throwable
     */
    public function the_email_must_be_a_valid_email_address() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['email' => 'invalid@email'])

        )->assertSessionHasErrors('email');
        
        
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_email_must_be_unique() {

        // Variables
        factory(User::class)->create(['email' => 'alberto@email.com']);

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['email' => 'alberto@email.com'])

        )->assertSessionHasErrors('email');


    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_password_is_required() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['password' => null])

        )->assertSessionHasErrors('password');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_password_must_be_a_string() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['password' => 1234])

        )->assertSessionHasErrors('password');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_password_must_be_at_least_6_characters_long() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData(['password' => str_random(5)])

        )->assertSessionHasErrors('password');
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function the_password_must_be_confirmed() {

        // Given
        $this->post(

            route('register'),
            $this->userValidData([

                'password' => 'secret',
                'password_confirmation' => null

            ])

        )->assertSessionHasErrors('password');
    }

    /**
     * @param array $overrides
     * @return array
     */
    public function userValidData($overrides = []): array
    {
        return array_merge([

            'name' => 'AlbertoRosas2',
            'first_name' => 'Alberto',
            'last_name' => 'Rosas',
            'email' => 'alberto@email.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'

        ], $overrides);
    }
}
