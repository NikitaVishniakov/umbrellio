<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'type'          => 'posts',
            'id'            => (string)$this->id,
            'attributes'    => [
                'header' => $this->header,
                'content' => $this->content
            ],
        ];

    }
}
