<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function auth_user_can_be_fetched(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $respone = $this->get('/api/user-auth');
        $respone->assertStatus(200)
            ->assertJson([
               'data' => [
                   'type' => 'users',
                   'user_id' => $user->id,
                   'attributes' => [
                       'name' => $user->name
                   ]
               ],
                'links' => [
                    'self' => url('/users/'. $user->id),
                ]
            ]);
    }
}
