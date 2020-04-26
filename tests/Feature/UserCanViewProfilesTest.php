<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanViewProfilesTest extends TestCase
{
    use refreshdatabase;
    /** @test */
    public function a_user_can_view_user_profiles(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $posts = factory(Post::class, 2)->create(['user_id'=> $user->id]);

        $response = $this->get('/api/users/'. $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' =>[
                    [
                        'data' => [
                            'type' => 'users',
                            'user_id' => $user->id,
                            'attributes' =>[
                                'body' => $posts->last()->body,
                                'posted_at' => [
                                    'name' => $user->name,
                                ]

                            ]
                        ]
                    ]
                ],
                'links' => [
                    'self' => url('/users/'. $user->id)
                ]
            ]);

    }
}
