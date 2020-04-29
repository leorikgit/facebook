<?php

namespace Tests\Feature;

use App\Friend;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FriendsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_send_firend_request(){
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $anotherUser =  factory(User::class)->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id
        ])->assertStatus(200);;

        $friendResponse = Friend::first();
        $this->assertNotNull($friendResponse);
        $this->assertEquals($user->id, $friendResponse->user_id);
        $this->assertEquals($anotherUser->id, $friendResponse->friend_id);

        $response->assertJson([
           'data' => [
               'type' => 'friends',
               'friends_id' => $friendResponse->id,
               'attributes' => [
                   'confirmed_at' => null
               ]
           ] ,
            'links' => [
                'self' => url('/users/'.$anotherUser->id)
            ]
        ]);

    }

    /** @test */
    public function only_existing_users_can_be_friends(){


        $this->actingAs($user = factory(User::class)->create(), 'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => 123
        ])->assertStatus(404);;

        $friendResponse = Friend::first();
        $this->assertNull($friendResponse);
        $response->assertJson([
            'errors'=>[
                'code' => 404,
                'title' => 'User Not Found',
                'detail' => 'Unable to locate the User with the given information.'
            ]
        ]);
    }
    /** @test */
    public function user_can_accept_friend_request(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $anotherUser = factory(User::class)->create();

        $this->post('/api/friend-request', ['friend_id' => $anotherUser->id])
        ->assertStatus(200);

        $response = $this->actingAs($anotherUser, 'api')
            ->post('/api/friend-request-response', ['user_id' => $user->id, 'status' => 1])->assertStatus(200);


        $friendRequest = Friend::first();
        $this->assertNotNull($friendRequest);
        $this->assertNotNull($friendRequest->confirmed_at);
        $this->assertInstanceOf(Carbon::class, $friendRequest->confirmed_at);
        $this->assertEquals(1, $friendRequest->status);
//        $this->assertEquals(now()->startOfSecond() , $friendRequest->confirmed_at);
        $response->assertJson([
            'data' => [
                'type' => 'friends',
                'friends_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendRequest->confirmed_at->diffForHumans()
                ]
            ] ,
            'links' => [
                'self' => url('/users/'.$anotherUser->id)
            ]
        ]);
    }
}
