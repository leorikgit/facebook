<?php

namespace App\Http\Controllers;

use App\Exceptions\FirendRequesNotFoundException;
use App\Friend;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Http\Resources\Friend as friendResource;
class FriendRequestResponseController extends Controller
{
    public function store(){

        $data = request()->validate([
            'user_id' => '',
            'status' => ''
        ]);

        try {
            $friend = Friend::where('user_id', $data['user_id'])->where('friend_id', auth()->user()->id)->firstORFail();
        }catch (ModelNotFoundException $e) {

            throw new FirendRequesNotFoundException();
        }

        $friend->update(array_merge($data, ['confirmed_at' => now()->diffForHumans()]));

        return new friendResource($friend);
    }
}
