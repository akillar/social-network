<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use App\Models\Status;
use App\Models\Comment;
use App\Traits\HasLikes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * @throws \Throwable
     */
    public function a_comment_model_must_use_trait_has_likes() {

        $this->assertClassUsesTrait(HasLikes::class, Status::class);

    }

    /** @test */
    public function a_status_belongs_to_a_user() {
    
        // Variables
        $status = factory(Status::class)->create();
    
        // Given
        $this->assertInstanceOf(User::class, $status->user);
        
    }

    /** @test */
    public function a_status_has_many_comments() {

        // Variables
        $status = factory(Status::class)->create();

        // Given
        factory(Comment::class)->create(['status_id' => $status->id]);

        // Then
        $this->assertInstanceOf(Comment::class, $status->comments->first());

    }

}
