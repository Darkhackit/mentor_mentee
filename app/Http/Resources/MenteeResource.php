<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenteeResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            'mentor' => $this->mentor,
            'posts' => PostResource::collection($this->posts),
            'post_count' => $this->posts->count(),
            'is_mentor' => $this->is_mentor,
            'mentor_id' => $this->mentor_id

        ];
    }
}
