<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body'=> $this->body,
            'image' => $this->image,
            'mentor' => $this->mentor,
            'mentee' => $this->mentee,
            'comments' => CommentResource::collection($this->comments),
            'comments_count' => $this->comments->count(),
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
