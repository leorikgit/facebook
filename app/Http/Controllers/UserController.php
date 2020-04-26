<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use \App\Http\Resources\User as userResource;

class UserController extends Controller
{
    public function show(User $user){

        return new userResource($user);
    }
}
