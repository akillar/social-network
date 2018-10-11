<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanLikeCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_users_cannot_like_comments() {


        $comment = factory(Comment::class)->create();

        // Given
        $response = $this->postJson(route('comments.likes.store', $comment));

        $response->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_like_and_unlike_comments() {

        $this->withoutExceptionHandling();

        // Variables
        $user = factory(User::Class)->create();
        $comment = factory(Comment::class)->create();

        $this->assertCount(0, $comment->fresh()->likes);

        // Given
        $this->actingAs($user)->postJson( route('comments.likes.store', $comment) );

        $this->assertCount(1, $comment->fresh()->likes);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id]);


        $this->actingAs($user)->deleteJson( route('comments.likes.destroy', $comment) );

        $this->assertCount(0, $comment->fresh()->likes);

        $this->assertDatabaseMissing('likes', ['user_id' => $user->id]);

    }
}
