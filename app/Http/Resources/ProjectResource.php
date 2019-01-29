<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProjectResource extends Resource
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
            'concerts' => ConcertResource::collection($this->concerts),
            'rehearsals' => RehearsalResource::collection($this->rehearsals),
            'semester' => new SemesterResource($this->semester)
        ];

        if ($this->resource->accepted !== null) {
            $project['accepted'] = $this->resource->accepted;
        }

        return $project;
    }
}
