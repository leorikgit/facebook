<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \App\Http\Resources\User as userResources;
class Post extends JsonResource
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
                'type' => 'posts',
                'post_id' => $this->id,
                'attributes' => [
                    'likes'=> new LikeCollection($this->likes),
                    'posted_by'=> new userResources($this->user),
                    'body' => $this->body,
                    'posted_at' => $this->created_at->diffForHumans(),
                    'image' => $this->image,
                ]
            ],
            'links' => [
                'self' => url('/posts/'.$this->id)
            ]
        ];
    }
}
