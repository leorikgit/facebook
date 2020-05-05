<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Comment;

class PostCommentsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_comment_post(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(['id'=> 123]), 'api');
        $post = factory(Post::class)->create(['id'=> 11]);

        $response = $this->post('/api/posts/'.$post->id.'/comment', ['body'=> 'awesome body comment'])->assertStatus(200);
        $comment = Comment::first();

        $this->assertCount(1, Comment::all());
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals($comment->body,'awesome body comment');

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'comments',
                        'comment_id' => 1,
                        'attributes' => [
                            'commented_by'=> [
                                'data' => [
                                    'type' => 'users',
                                    'user_id' => $user->id,
                                    'attributes' => [
                                        'name' => $user->name,
                                    ]
                                ]
                            ],
                            'body' => $comment->body,
                            'commented_at' => $comment->created_at->diffForHumans()
                        ]

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
    /** @test */
    public function comment_need_valid_body(){

        $this->actingAs($user = factory(User::class)->create(['id'=> 123]), 'api');
        $post = factory(Post::class)->create(['id'=> 11]);

        $response = $this->post('/api/posts/'.$post->id.'/comment')->assertStatus(422);

        $responseStrong = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('body', $responseStrong['errors']['meta']);
    }
    /** @test */
    public function posts_are_returned_with_comments(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(['id'=> 123]), 'api');
        $post = factory(Post::class)->create(['id'=> 10, 'user_id' => $user->id]);

        $this->post('/api/posts/'.$post->id.'/comment', ['body'=> 'awesome body comment'])->assertStatus(200);


        $response = $this->get('/api/posts/')->assertStatus(200);


        $comment = Comment::first();

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'attributes' => [
                            'comments'=> [
                                'data' => [
                                    [
                                        'data' => [
                                            'type' => 'comments',
                                            'comment_id' => 1,
                                            'attributes' => [
                                                'commented_by'=> [
                                                    'data' => [
                                                        'type' => 'users',
                                                        'user_id' => $user->id,
                                                        'attributes' => [
                                                            'name' => $user->name,
                                                        ]
                                                    ]
                                                ],
                                                'body' => $comment->body,
                                                'commented_at' => $comment->created_at->diffForHumans()
                                            ]

                                        ],
                                        'links' => [
                                            'self' => url('/posts/11')
                                        ]
                                    ]

                                ],
                                'comment_count' => 1
                            ]
                        ],
                        'type' => 'posts',
                        'post_id' => 10
                    ],
                    'links' => [
                        'self' => url('/posts/10')
                    ]
                ],

            ],
            'links' => [
                'self' => url('/posts')
            ]
        ]);
    }
}
