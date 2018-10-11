<?php

namespace Tests\Unit\Models;

use App\Traits\HasLikes;
use App\User;
use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    
    /** 
     * @test
     * @throws \Throwable
     */
    public function a_comment_model_must_use_trait_has_likes() {

        $this->assertClassUsesTrait(HasLikes::class, Comment::class);
        
    }

    /** 
     * @test
     * @throws \Throwable
     */
    public function a_comment_belongs_to_a_user() {
    
        // Variables
        $comment = factory(Comment::class)->create();
    
        $this->assertInstanceOf(User::class, $comment->user);

    }

}
