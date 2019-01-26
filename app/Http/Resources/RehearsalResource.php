<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RehearsalResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
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
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'place' => $this->place,
            //'promises' => UserResource::collection($this->promises),
            //'denials' => UserResource::collection($this->denials)
        ];

        if ($this->resource->accepted !== null) {
            $rehearsal['accepted'] = $this->resource->accepted;
        }

        return $rehearsal;
    }
}
