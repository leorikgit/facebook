<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \App\Http\Resources\Friend as friendResource;
use \App\Friend;
use \App\Http\Resources\UserImage as userImageResource;
class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
        'data' => [
            'type' => 'users',
            'user_id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'friendship' => new friendResource(Friend::friendship($this->id)),
                'cover_image' => new userImageResource($this->coverImage),
                'profile_image' =>  new userImageResource($this->profileImage),
            ]
        ],
        'links' => [
            'self' => url('/users/'.$this->id)
        ]
    ];
    }
}
