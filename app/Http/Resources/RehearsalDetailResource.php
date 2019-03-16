<?php

namespace App\Http\Resources;

use App\Rehearsal;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class RehearsalDetailResource extends Resource
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
        $rehearsal = [
            'id' => $this->id,
            'title' => __('Rehearsal') . ': ' .  $this->date->format('d.m.Y'),
            'date' => $this->date->format(\DateTime::ATOM),
            'project' => (object)[
                'id' => $this->project->id,
                'title' => $this->project->title
            ],
            'semester' => (object)[
                'id' => $this->semester->id
            ],
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'place' => $this->place,
            'accepted' => $this->promises->find(Auth::user()) !== null,
            'declined' => $this->denials->find(Auth::user()) !== null,
            'deadline' => $this->deadline,
            'other_rehearsals' => RehearsalResource::collection($this->project->rehearsals->filter(function (Rehearsal $rehearsal) {
                return $rehearsal->id !== $this->id;
            })->values()),
            'description' => $this->description,
            'project_id' => $this->project_id,
            'semester_id' => $this->semester_id
        ];

        return $rehearsal;
    }
}
