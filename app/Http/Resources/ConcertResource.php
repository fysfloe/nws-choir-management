<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class ConcertResource extends Resource
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
            'title' => $this->title,
            'description' => $this->description,
            'project' => (object)[
                'id' => $this->project->id,
                'title' => $this->project->title
            ],
            'date' => (new \DateTime($this->date))->format('d.m.Y'),
            'start_time' => (new \DateTime($this->start_time))->format('H:i'),
            'end_time' => (new \DateTime($this->end_time))->format('H:i'),
            'rehearsals' => RehearsalResource::collection($this->project->rehearsals),
            'semester_id' => $this->project->semester_id,
            'has_accepted' => $this->promises->find(Auth::user()) !== null,
            'has_declined' => $this->denials->find(Auth::user()) !== null,
            'deadline' => $this->deadline
        ];
    }
}
