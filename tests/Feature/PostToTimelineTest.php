<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function user_can_post_text_post()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => 'super body',
                ]
            ]
        ]);
        $post = Post::first();
        $this->assertCount(1, Post::all());
        $this->assertEquals($user->id, $post->id);
        $this->assertEquals('super body', $post->body);
        $response->assertStatus(201)
        ->assertJson([
            'data' => [
                'type'          => 'posts',
                'post_id'       => $post->id,
                'attributes'    => [
                    'posted_by' => [
                        'data' => [
                            'attributes' => [
                                'name' => $user->name
                            ]
                        ]
                    ],
                    'body'      => $post->body,
                ]
            ],
            'links' => [
                'self' => url('posts/'.$post->id)
            ]
        ]);

    }
}
