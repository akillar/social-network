<?php

namespace Tests\Feature;

use App\Models\Status;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCommentTest extends TestCase
{

    use RefreshDatabase;
    
    /** 
     * @test
     * @throws \Throwable
     */
    public function guest_users_cannot_create_comments() {
    
        // Variables
        $status = factory(Status::class)->create();
        $comment = ['body' => 'Mi primer comentario'];

        // Given
        $response = $this->postJson(route('statuses.comments.store', $status), $comment);

        // When
        $response->assertStatus(401); // unauthorized
        
        // Then

    }

    /** 
     * @test
     * @throws \Throwable
     */
    public function authenticated_users_can_comment_statuses() {

        // Variables
        $status = factory(Status::class)->create();
        $user = factory(User::class)->create();
        $comment = ['body' => 'Mi primer comentario'];

        // Given
        $response = $this->actingAs($user)
                        ->postJson(route('statuses.comments.store', $status), $comment);

        $response->assertJson([
           'data' => ['body' => $comment['body']]
        ]);
        
        // Then
        $this->assertDatabaseHas('comments', [
           'user_id' => $user->id,
           'status_id' => $status->id,
            'body' => $comment['body']
        ]);
        
        
    }

    /** @test */
    public function a_comment_requires_a_body() {

        // Given
        $status = factory(Status::class)->create();
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // When
        $response = $this->postJson(route('statuses.comments.store', $status), [
            'body' => ''
        ]);

        $response->assertStatus(422);

        // assertJsonStructure = Verifica solo las llaves { 'llave' => 'valor' } del JSON
        $response->assertJsonStructure([
            'message',
            'errors' => ['body']
        ]);

    }
}
