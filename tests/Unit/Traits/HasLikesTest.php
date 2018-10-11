<?php

namespace Tests\Unit\Traits;

use App\User;
use Tests\TestCase;
use App\Models\Like;
use App\Traits\HasLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasLikesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_model_morph_many_likes() {

        $model = new ModelWithLikes(['id' => 1]);

        // Given
        factory(Like::class)->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        // Then
        $this->assertInstanceOf(Like::class, $model->likes->first());

    }

    /** @test */
    public function a_model_can_be_liked_and_unlike() {

        // Variables
        $model = new ModelWithLikes(['id' => 1]);

        // Given
        $this->actingAs(factory(User::class)->create());

        // When
        $model->like();

        // Then
        $this->assertEquals(1, $model->likes()->count());

        $model->unlike();

        $this->assertEquals(0, $model->likes()->count());

    }

    /** @test */
    public function a_model_can_be_liked_once() {

        // Variables
        $model = new ModelWithLikes(['id' => 1]);

        // Given
        $this->actingAs(factory(User::class)->create());

        // When
        $model->like();

        // Then
        $this->assertEquals(1, $model->likes->count());

        $model->like();

        $this->assertEquals(1, $model->likes->count());
    }

    /** @test */
    public function a_model_knows_if_it_has_been_liked() {

        // Variables
        $model = new ModelWithLikes(['id' => 1]);

        $this->assertFalse($model->isLiked());

        // Given
        $this->actingAs(factory(User::class)->create());

        $model->like();

        $this->assertTrue($model->isLiked());
    }

    /** @test */
    public function a_model_knows_how_many_likes_it_has() {

        // Variables
        $model = new ModelWithLikes(['id' => 1]);

        $this->assertEquals(0, $model->likesCount());

        factory(Like::class, 2)->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        // Given
        $this->assertEquals(2, $model->likesCount());

    }
}


class ModelWithLikes extends Model {

    use HasLikes;
    
    protected $fillable = ['id'];
    
}
