<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'accepted' => $this->commentable->promises->contains($this->user),
            'declined' => $this->commentable->denials->contains($this->user),
            'created_at' => (new \DateTime($this->created_at))->format('d.m.Y H:i'),
            'user' => new UserResource($this->user),
            'comment' => $this->comment,
            'private' => $this->private
        ];
    }
}
