<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class ProjectListResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $project = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'creator' => $this->creator->firstname . ' ' . $this->creator->surname,
            'created_at' => $this->created_at->format('d.m.Y'),
            'accepted' => $this->promises->contains(Auth::user()),
            'declined' => $this->denials->contains(Auth::user())
        ];
        return $project;
    }
}
