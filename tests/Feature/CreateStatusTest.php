<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Status;
use App\Events\StatusCreated;
use Illuminate\Support\Facades\Event;
use App\Http\Resources\StatusResource;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateStatusTest extends TestCase
{

    use RefreshDatabase;

    protected $body = 'Mi first comment';

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
    
        Event::fake([StatusCreated::class]);
    
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->postJson(route('statuses.store'), [
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
    
    /**
    *   @test
    *   @throws \Throwable
    */
    public function an_event_is_fired_when_a_status_is_created() {
    
        Event::fake([StatusCreated::class]);
    
        Broadcast::shouldReceive('socket')->andReturn('socket-id');
    
        // Variables
    
        // Given
        $user = factory(User::class)->create();
        $this->actingAs($user)->postJson(route('statuses.store'), [
            'body' => $this->body
        ]);
    
        Event::assertDispatched(StatusCreated::class, function ($statusCreatedEvent) {
        
            $this->assertInstanceOf(ShouldBroadcast::class, $statusCreatedEvent);
            $this->assertInstanceOf(StatusResource::class, $statusCreatedEvent->status);
            $this->assertInstanceOf(Status::class, $statusCreatedEvent->status->resource);
        
            $this->assertEquals(Status::first()->id, $statusCreatedEvent->status->id);
            $this->assertEquals(
            
                'socket-id',
                $statusCreatedEvent->socket,
                'The event ' . get_class($statusCreatedEvent) .
                ' must call the method "dontBroadcastToCurrentUser" in the constructor.'
        
            );
        
            return true;
        
        });
    
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
