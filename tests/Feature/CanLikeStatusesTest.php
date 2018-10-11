<?php

namespace Tests\Feature;

use App\Models\Status;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanLikeStatusesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_cannot_like_statuses() {

        $status = factory(Status::class)->create();

        // Given
        $response = $this->postJson(route('statuses.likes.store', $status));

        $response->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_like_and_unlike_statuses() {

        $this->withoutExceptionHandling();

        // Variables
        $user = factory(User::Class)->create();
        $status = factory(Status::class)->create();

        $this->assertCount(0, $status->likes);

        // Given
        $this->actingAs($user)->postJson( route('statuses.likes.store', $status) );

        $this->assertCount(1, $status->fresh()->likes);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id,]);


        $this->actingAs($user)->deleteJson( route('statuses.likes.destroy', $status) );

        $this->assertCount(0, $status->fresh()->likes);

        $this->assertDatabaseMissing('likes', ['user_id' => $user->id]);
        
    }

}
