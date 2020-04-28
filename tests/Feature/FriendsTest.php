<?php

namespace Tests\Feature;

use App\Friend;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function send_firend_request(){
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
}