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
        $dateTime = new \DateTime($this->date);
        $startTime = new \DateTime($this->start_time);
        $dateTime->setTime($startTime->format('H'), $startTime->format('i'));
        $now = new \DateTime();

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
            'promises' => UserResource::collection($this->promises),
            'denials' => UserResource::collection($this->denials),
            'isOver' => $dateTime < $now
        ];

        if ($this->resource->accepted !== null) {
            $rehearsal['accepted'] = $this->resource->accepted;
        }

        return $rehearsal;
    }
}
