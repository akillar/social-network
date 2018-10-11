<?php

namespace Tests\Feature;

use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListStatusesTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function can_get_all_statuses() {

        // Variables
        $this->withoutExceptionHandling();


        // Given
        $status1 = factory(Status::class)->create(['created_at' => now()->subDays(4)]);
        $status2 = factory(Status::class)->create(['created_at' => now()->subDays(3)]);
        $status3 = factory(Status::class)->create(['created_at' => now()->subDays(2)]);
        $status4 = factory(Status::class)->create(['created_at' => now()->subDays(1)]);

        $response = $this->getJson(route('statuses.index'));

        // When
        $response->assertSuccessful();

        $response->assertJson([
            'meta' => ['total' => 4]
        ]);

        // Then
        $response->assertJsonStructure([
            'data',
            'links' => [
                'prev',
                'next'
            ]
        ]);

        $this->assertEquals(
            $response->json('data.0.body'),
            $status4->body
        );
    }
}
