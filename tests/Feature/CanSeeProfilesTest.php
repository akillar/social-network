<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanSeeProfilesTest extends TestCase
{

    use RefreshDatabase;

    /** 
     * @test
     * @throws \Throwable
     */
    public function can_see_profiles_test() {

        $this->withoutExceptionHandling();
    
        // Variables
        factory(User::class)->create(['name' => 'Alberto']);
    
        // Given
        $this->get('@Alberto')
            ->assertSee('Alberto');
        
        // When
        
        
        // Then
        
        
    }
}
