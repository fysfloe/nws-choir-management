<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class DashboardResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $semester = [
            'id' => $this->id,
            'name' => $this->name,
            'start_date' => (new \DateTime($this->start_date))->format('d.m.Y'),
            'end_date' => (new \DateTime($this->end_date))->format('d.m.Y'),
            'projects' => ProjectResource::collection($this->projects),
            'concerts' => ConcertResource::collection($this->concerts),
            'rehearsals' => RehearsalResource::collection($this->rehearsals),
            'accepted' => $this->promises->contains(Auth::user()),
            'declined' => $this->denials->contains(Auth::user()),
        ];

        if ($this->resource->accepted !== null) {
            $semester['accepted'] = $this->resource->accepted;
        }

        return $semester;
    }
}
