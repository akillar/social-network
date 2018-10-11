<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** 
     * @test
     * @throws \Throwable
     */
    public function route_key_name_is_set_to_name() {
    
        // Variables
        $user = factory(User::class)->make();
        
        // Then
        $this->assertEquals(
            'name',
            $user->getRouteKeyName(),
            'The route key name must be name'
        );
        
    }
    
    /** 
     * @test
     * @throws \Throwable
     */
    public function user_has_link_to_their_profile() {
    
        // Variables
        $user = factory(User::class)->make();
        
        // Then
        $this->assertEquals(route('users.show', $user), $user->link());
        
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function user_has_an_avatar() {

        // Variables
        $user = factory(User::class)->make();

        // Then
        $this->assertEquals('https://aprendible.com/images/default-avatar.jpg', $user->avatar());
        $this->assertEquals('https://aprendible.com/images/default-avatar.jpg', $user->avatar);

    }

}
