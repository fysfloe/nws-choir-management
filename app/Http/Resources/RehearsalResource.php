<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class RehearsalResource extends Resource
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
            'start_time' => (new \DateTime($this->start_time))->format('H:i'),
            'end_time' => (new \DateTime($this->end_time))->format('H:i'),
            'place' => $this->place,
            'accepted' => $this->promises->find(Auth::user()) !== null,
            'declined' => $this->denials->find(Auth::user()) !== null,
            'deadline' => $this->deadline,
        ];

        if ($this->resource->accepted !== null) {
            $rehearsal['accepted'] = $this->resource->accepted;
        }

        return $rehearsal;
    }
}
