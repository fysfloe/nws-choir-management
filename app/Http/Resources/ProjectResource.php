<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class ProjectResource extends Resource
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
        $project = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'creator' => $this->creator->firstname . ' ' . $this->creator->surname,
            'created_at' => $this->created_at->format('d.m.Y'),
            'concerts' => ConcertResource::collection($this->concerts),
            'rehearsals' => RehearsalResource::collection($this->rehearsals),
            'comments' => CommentResource::collection($this->comments),
            'accepted' => $this->promises->contains(Auth::user()),
            'declined' => $this->denials->contains(Auth::user()),
            'deadline' => (new \DateTime($this->deadline))->format('Y-m-d\TH:i'),
            'semester_id' => $this->semester->id,
            'is_main' => $this->is_main
        ];

        return $project;
    }
}
