<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use \App\Http\Resources\Friend as friendResource;
class FriendRequestController extends Controller
{
    public function store(){

        $data = request()->validate([
           'friend_id' => ''
        ]);
//        User::findOrFail($data['friend_id'])->friends()->attach(auth()->user());
        $friend = User::findOrFail($data['friend_id']);
        auth()->user()->friends()->attach($friend);


        return new friendResource(Friend::where('friend_id', $data['friend_id'])->where('user_id', auth()->user()->id)->firsT());
    }
}
