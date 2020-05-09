<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserImage extends JsonResource
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
            'data'=>[
                'type' => 'user_images',
                'user_images_id' => $this->id,
                'attributes' => [
                    'path' => url($this->path),
                    'location' => $this->location,
                    'width' => $this->width,
                    'height' => $this->height
                ]
            ],
            'links' => [
                'self' => url('users/'. $this->user_id)
            ]
        ];
    }
}
