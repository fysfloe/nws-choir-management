<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

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
            'hasAccepted' => $this->promises->find(Auth::user()) !== null,
            'hasDeclined' => $this->denials->find(Auth::user()) !== null
        ];

        if ($this->resource->accepted !== null) {
            $rehearsal['accepted'] = $this->resource->accepted;
        }

        return $rehearsal;
    }
}
