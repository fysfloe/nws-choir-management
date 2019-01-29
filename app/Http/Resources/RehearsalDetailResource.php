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
            'title' => $this->date->format('d.m.Y'),
            'date' => [
                'day' => $this->date->format('d'),
                'month' => $this->date->format('m')
            ],
            'project' => (object)[
                'id' => $this->project->id,
                'title' => $this->project->title
            ],
            'semester' => (object)[
                'id' => $this->semester->id
            ],
            'start_time' => (new \DateTime($this->start_time))->format('H:i'),
            'end_time' => (new \DateTime($this->end_time))->format('H:i'),
            'place' => $this->place,
            'has_accepted' => $this->promises->find(Auth::user()) !== null,
            'has_declined' => $this->denials->find(Auth::user()) !== null,
            'deadline' => $this->deadline,
            'other_rehearsals' => RehearsalResource::collection($this->project->rehearsals->filter(function (Rehearsal $rehearsal) {
                return $rehearsal->id !== $this->id;
            })->values())
        ];

        return $rehearsal;
    }
}
