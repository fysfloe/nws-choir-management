<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class ConcertListResource extends Resource
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
            'date' => $this->date,
            'start_time' => (new \DateTime($this->start_time))->format('H:i'),
            'end_time' => (new \DateTime($this->end_time))->format('H:i'),
            'accepted' => $this->promises->contains(Auth::user()),
            'declined' => $this->denials->contains(Auth::user()),
            'deadline' => (new \DateTime($this->deadline))->format('Y-m-d\TH:i'),
            'project' => $this->project ? $this->project->title : null
        ];
    }
}
