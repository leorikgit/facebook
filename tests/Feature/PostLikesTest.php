<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostLikesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_toggle_post_likes(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $post = factory(Post::class)->create(['id'=> 123]);
        $response = $this->post('/api/posts/'.$post->id.'/like')->assertStatus(200);

        $likes = $post->likes;
        $this->assertCount(1, $likes);

        $response->assertJson([
          'data' => [
              [
                  'data' => [
                      'attributes' => [],
                      'type' => 'likes',
                      'like_id' => 1
                  ],
                  'links' => [
                      'self' => url('/posts/123')
                  ]
              ]
          ],
            'links' => [
                'self' => url('/posts')
            ]
        ]);

    }
}
