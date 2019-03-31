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
            'projects' => ProjectListResource::collection($this->projects->take(10)),
            'concerts' => ConcertListResource::collection($this->concerts->take(10)),
            'rehearsals' => RehearsalListResource::collection($this->rehearsals->take(10)),
            'accepted' => $this->promises->contains(Auth::user()),
            'declined' => $this->denials->contains(Auth::user()),
        ];

        if ($this->resource->accepted !== null) {
            $semester['accepted'] = $this->resource->accepted;
        }

        return $semester;
    }
}
