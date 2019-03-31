<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class SemesterResource extends Resource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'projects' => ProjectListResource::collection($this->projects),
            'accepted' => $this->promises->contains(Auth::user()),
            'declined' => $this->denials->contains(Auth::user()),
            'deadline' => $this->deadline
        ];

        if ($this->resource->accepted !== null) {
            $semester['accepted'] = $this->resource->accepted;
        }

        return $semester;
    }
}
