<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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
            'start_date' => (new \DateTime($this->start_date))->format('d.m.Y'),
            'end_date' => (new \DateTime($this->end_date))->format('d.m.Y'),
            'promises' => UserResource::collection($this->promises),
            'denials' => UserResource::collection($this->denials)
        ];

        if ($this->resource->accepted !== null) {
            $semester['accepted'] = $this->resource->accepted;
        }

        return $semester;
    }
}
