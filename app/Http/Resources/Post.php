<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \App\Http\Resources\User as userResources;
use App\Http\Resources\CommentCollection as commentsCollection;
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
                    'comments' => new commentsCollection($this->comments),
                    'body' => $this->body,
                    'posted_at' => $this->created_at->diffForHumans(),
                    'image' => $this->image ? 'storage/'.$this->image : null,
                ]
            ],
            'links' => [
                'self' => url('/posts/'.$this->id)
            ]
        ];
    }
}
