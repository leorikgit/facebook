<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Resources\PostCollection;
use App\Post;
use Illuminate\Http\Request;
use \App\Http\Resources\Post as postReource;

class PostController extends Controller
{
    public function index(){
        $friends = Friend::friendships();
        if($friends->isEmpty()){
            return new PostCollection(request()->user()->posts);
        }
        return new PostCollection(Post::WhereIn('user_id', [$friends->pluck('user_id'), $friends->pluck('friend_id')])->get());
    }
    public function store(){
        $data = request()->validate([
            'data.attributes.body' => ''
        ]);
        $post = request()->user()->posts()->create($data['data']['attributes']);


       return new postReource($post);
    }
}
