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
    public function user_can_send_firend_request_only_once(){
        $this->withoutExceptionHandling();

        $this->actingAs($user = factory(User::class)->create(), 'api');

        $anotherUser =  factory(User::class)->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id
        ])->assertStatus(200);;
        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id
        ])->assertStatus(200);;

        $friendResponse = Friend::all();
        $this->assertCount(1, $friendResponse);

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

        $response->assertJson([
            'data' => [
                'type' => 'friends',
                'friends_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendRequest->confirmed_at->diffForHumans(),
                    'friend_id' => $friendRequest->friend_id,
                    'user_id' => $friendRequest->user_id,
                ]
            ] ,
            'links' => [
                'self' => url('/users/'.$anotherUser->id)
            ]
        ]);
    }
    /** @test */
    public function only_valid_friend_request_can_be_accepted(){

        $anotherUser = factory(User::class)->create();
        $this->actingAs($anotherUser, 'api');
        $response = $this->post('/api/friend-request-response', ['user_id' => 123, 'status' => 1])->assertStatus(404);

        $friendRequest = Friend::first();
        $this->assertNull($friendRequest);
        $response->assertJson([
            'errors'=>[
                'code' => 404,
                'title' => 'Friend Request Not Found',
                'detail' => 'Unable to locate friend request with the given information.'
            ]
        ]);
    }

    /** @test */
    public function only_recipient_can_accept_friend_request(){
//        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $anotherUser = factory(User::class)->create();

        $this->post('/api/friend-request', ['friend_id' => $anotherUser->id])
            ->assertStatus(200);

        $response = $this->actingAs( factory(User::class)->create(), 'api')
            ->post('/api/friend-request-response', ['user_id' => $user->id, 'status' => 1])->assertStatus(404);
        $response->assertJson([
            'errors'=>[
                'code' => 404,
                'title' => 'Friend Request Not Found',
                'detail' => 'Unable to locate friend request with the given information.'
            ]
        ]);
    }
    /** @test */
    public function valid_friend_id_for_friend_request(){
//        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $response = $this->post('/api/friend-request', ['friend_id' => '']);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('friend_id', $responseString['errors']['meta']);
    }
    /** @test */
    public function a_user_id_and_status_is_required_for_friend_response(){


        $response = $this->actingAs($user = factory(User::class)->create(), 'api')
            ->post('/api/friend-request-response', ['user_id' =>'', 'status' => ''])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user_id', $responseString['errors']['meta']);
        $this->assertArrayHasKey('status', $responseString['errors']['meta']);
    }

    /** @test */
    public function a_friendship_is_retrieve_when_fetching_the_profile(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $anotherUser = factory(User::class)->create();
        $friendRequest = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $anotherUser->id,
            'confirmed_at' => now()->subDay(),
            'status' => 1
        ]);
        $response = $this->get('/api/users/'. $anotherUser->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' =>[
                        'friendship' =>[
                            'data' => [
                                'attributes'=>[
                                    'confirmed_at' => '1 day ago'
                                ]
                            ]
                        ]
                    ]
                ],
            ]);
    }
    /** @test */
    public function inverse_a_friendship_is_retrieve_when_fetching_the_profile(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $anotherUser = factory(User::class)->create();
        $friendRequest = Friend::create([
            'user_id' => $anotherUser->id,
            'friend_id' => $user->id,
            'confirmed_at' => now()->subDay(),
            'status' => 1
        ]);
        $response = $this->get('/api/users/'. $anotherUser->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' =>[
                        'friendship' =>[
                            'data' => [
                                'attributes'=>[
                                    'confirmed_at' => '1 day ago'
                                ]
                            ]
                        ]
                    ]
                ],
            ]);
    }
    /** @test */
    public function friend_request_can_be_ignored(){
        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $anotherUser = factory(User::class)->create();

        $this->post('/api/friend-request', ['friend_id' => $anotherUser->id])
            ->assertStatus(200);

        $response = $this->actingAs($anotherUser, 'api')
            ->delete('/api/friend-request-response/delete', ['user_id' => $user->id, 'status' => 1])->assertStatus(204);

        $firendReqest = Friend::first();
        $this->assertNull($firendReqest);
        $response->assertNoContent();
    }

    /** @test */
    public function only_recipient_can_ignore_friend_request(){
//        $this->withoutExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');
        $anotherUser = factory(User::class)->create();

        $this->post('/api/friend-request', ['friend_id' => $anotherUser->id])
            ->assertStatus(200);

        $response = $this->actingAs( factory(User::class)->create(), 'api')
            ->delete('/api/friend-request-response/delete', ['user_id' => $user->id, 'status' => 1])->assertStatus(404);
        $firnedRequest = Friend::first();
        $this->assertNull($firnedRequest->confimed_at);
        $this->assertNull($firnedRequest->status);
        $response->assertJson([
            'errors'=>[
                'code' => 404,
                'title' => 'Friend Request Not Found',
                'detail' => 'Unable to locate friend request with the given information.'
            ]
        ]);
    }
}
