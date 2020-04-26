<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrivePostsTest extends TestCase
{
    use refreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_retive_posts()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $posts = factory(Post::class, 2)->create(['user_id'=> $user->id]);

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertJson([
                   'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->first()->id,
                            'attributes' => [
                                'body' => $posts->first()->body,
                                'posted_at' => $posts->first()->created_at->diffForHumans(),
                                'image' => $posts->first()->image

                            ]
                        ]
                    ],
                   [
                       'data' => [
                           'type' => 'posts',
                           'post_id' => $posts->last()->id,
                           'attributes' => [
                               'body' => $posts->last()->body,
                               'posted_at' => $posts->last()->created_at->diffForHumans(),
                               'image' => $posts->last()->image
                           ]
                       ]
                   ]
               ],
                'links' =>[
                    'self' => url('/posts')
                ]

            ]);
    }
    /** @test */
    public function user_can_only_retive_his_posts(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $posts = factory(Post::class)->create();
        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [],
                'links' => [
                    'self' => url('/posts')
                ]
            ]);
    }
}
