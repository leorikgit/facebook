<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostLikesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_toggle_post_likes(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(['id'=> 222]), 'api');
        $post = factory(Post::class)->create(['id'=> 11]);
        $this->post('/api/posts/'.$post->id.'/like')->assertStatus(200);
        $this->actingAs($user2 = factory(User::class)->create(), 'api');
        $response = $this->post('/api/posts/'.$post->id.'/like')->assertStatus(200);

        $likes = $post->likes;
        $this->assertCount(2, $likes);

        $response->assertJson([
          'data' => [
              [
                  'data' => [
                      'attributes' => [],
                      'type' => 'likes',
                      'like_id' => 222
                  ],
                  'links' => [
                      'self' => url('/posts/11')
                  ]
              ],
              [

                  'data' => [
                      'attributes' => [],
                      'type' => 'likes',
                      'like_id' => 224
                  ],
                  'links' => [
                      'self' => url('/posts/11')
                  ]
              ]
          ],
            'links' => [
                'self' => url('/posts')
            ]
        ]);

    }

    /** @test */
    public function a_user_can_retrive_posts_with_likes(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(['id'=> 123]), 'api');
        $post = factory(Post::class)->create(['id'=> 11, 'user_id' => $user->id]);

        $this->post('/api/posts/'.$post->id.'/like')->assertStatus(200);

        $response = $this->get('/api/posts/')->assertStatus(200);
        $likes = $post->likes;

        $this->assertCount(1, $likes);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'attributes' => [
                            'likes'=> [
                                'data' => [
                                    [
                                        'data'=>[
                                            'type' => 'likes',
                                            'like_id' => 123,
                                            'attributes' => []
                                        ]
                                    ]
                                ],
                                'like_count' => 1,
                                'user_likes_post' => true
                            ]
                        ],
                        'type' => 'posts',
                        'post_id' => 11
                    ],
                    'links' => [
                        'self' => url('/posts/11')
                    ]
                ],

            ],
            'links' => [
                'self' => url('/posts')
            ]
        ]);

    }
}
