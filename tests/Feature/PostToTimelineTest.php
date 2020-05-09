<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_post_text_post()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $response = $this->post('/api/posts', [
                'body' => 'super body',
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
    /** @test */
    public function user_can_post_text_post_with_image()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $img = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('/api/posts', [
            'body' => 'super body',
            'image' => $img,
            'width' => 1300,
            'height' => 400
        ]);
        Storage::disk('public')->assertExists('post-images/'.$img->hashName());

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
                       'image'      => url('storage/post-images/'.$img->hashName()),
                        'body'      => $post->body,
                    ]
                ],
            ]);

    }
}
