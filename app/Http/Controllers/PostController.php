<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Resources\PostCollection;
use App\Post;
use Illuminate\Http\Request;
use \App\Http\Resources\Post as postReource;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
            'body' => '',
            'image' => '',
            'width' => '',
            'height' => ''
        ]);
        if(isset($data['image'])){
            $img = $data['image']->store('post-images', 'public');
            Image::make($data['image'])->fit($data['width'], $data['height'])->save(storage_path('app/public/'.$img));
        }
        $post = request()->user()->posts()->create([
            'body' => $data['body'],
            'image' => $img ?? null
        ]);


       return new postReource($post);
    }
}
