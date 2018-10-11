<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStatusTest extends TestCase
{

    use RefreshDatabase;

    protected $body = 'Mi primer status';

    /** @test */
    public function guests_users_cannot_create_statuses() {

        // Given
        $response = $this->postJson(route('statuses.store'), [
            'body' => $this->body
        ]);

        $response->assertStatus(401);
        
    }

    /** @test */
    public function an_authenticated_user_can_create_status() {

        $this->withoutExceptionHandling();

        // Variables

        // Given
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // When
        $response = $this->postJson(route('statuses.store'), [
            'body' => $this->body
        ]);

        $response->assertJson([
            'data' => [
                'body' => $this->body
            ]
        ]);

        // Then
        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body' => $this->body
        ]);


    }
    
    /** @test */
    public function a_status_requires_a_body() {

        // Given
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // When
        $response = $this->postJson(route('statuses.store'), [
            'body' => ''
        ]);

        $response->assertStatus(422);

        // assertJsonStructure = Verifica solo las llaves { 'llave' => 'valor' } del JSON
        $response->assertJsonStructure([
            'message',
            'errors' => ['body']
        ]);
        
    }

    /** @test */
    public function a_status_body_requires_a_minimum_length() {

        // Given
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // When
        $response = $this->postJson(route('statuses.store'), [
            'body' => '1234'
        ]);

        $response->assertStatus(422);

        // assertJsonStructure = Verifica solo las llaves { 'llave' => 'valor' } del JSON
        $response->assertJsonStructure([
            'message',
            'errors' => ['body']
        ]);

    }
    
}
