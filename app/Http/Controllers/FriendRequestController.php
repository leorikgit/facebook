<?php

namespace App\Http\Controllers;

use App\Exceptions\UserModelNotFoundException;
use App\Exceptions\ValidationErrorException;
use App\Friend;
use App\User;

use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Http\Resources\Friend as friendResource;
use Illuminate\Validation\ValidationException;


class FriendRequestController extends Controller
{
    public function store(){

        $data = request()->validate([
            'friend_id' => 'required'
        ]);

        try {
            $friend = User::findOrFail($data['friend_id']);
        }catch (ModelNotFoundException $e){
            throw new UserModelNotFoundException();
        }
        // auth()->user()->friends()->attach($friend);
        $friend->friends()->syncWithoutDetaching(auth()->user());

        return new friendResource(Friend::where('friend_id', $data['friend_id'])->where('user_id', auth()->user()->id)->first());
    }
}
