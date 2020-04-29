<?php

namespace App\Http\Controllers;

use App\Friend;
use Illuminate\Http\Request;
use \App\Http\Resources\Friend as friendResource;
class FriendRequestResponseController extends Controller
{
    public function store(){

        $data = request()->validate([
            'user_id' => '',
            'status' => ''
        ]);

        $friend = Friend::where('user_id', $data['user_id'])->where('friend_id', auth()->user()->id)->firstOrFail();

        $friend->update(array_merge($data, ['confirmed_at' => now()->diffForHumans()]));

        return new friendResource($friend);
    }
}
