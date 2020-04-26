<?php

namespace Tests\Feature;

use App\Http\Resources\User as userResources;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_fetch_posts_for_profile(){
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create(['user_id'=> $user->id]);

        $response = $this->get('/api/users/'.$user->id.'/posts');
        $response->assertStatus(200)
            ->assertJson([
                'data'=> [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->id,
                            'attributes' => [
                                'posted_by'=> [
                                  'data' => [
                                      'attributes' => [
                                          'name' => $user->name
                                      ]
                                  ]
                                ],
                                'body' => $post->body,
                                'posted_at' => $post->created_at->diffForHumans(),
                                'image' => $post->image,
                            ]
                        ],
                        'links'=> [
                            'self' => url('/posts/'.$post->id)
                        ]
                    ]

                ]
            ]);
    }
}
