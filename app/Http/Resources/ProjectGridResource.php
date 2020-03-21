<?php

namespace App\Http\Resources;

use App\Rehearsal;
use Illuminate\Http\Resources\Json\Resource;

class ProjectGridResource extends Resource
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
        $concertsAndRehearsals = array_merge($this->rehearsals->all(), $this->concerts->all());

        usort($concertsAndRehearsals, function ($a, $b) {
            return $a->date > $b->date;
        });

        $grid = [];

        foreach ($concertsAndRehearsals as $concertOrReharsal) {
            $date = $concertOrReharsal->date;

            if ($concertOrReharsal instanceof Rehearsal) {
                $type = 'rehearsal';
            } else {
                $type = 'concert';
            }

            $addToGrid = [
                'type' => $type,
                'date' => (new \DateTime($date))->format('d.m.Y'),
                'participants' => [],
                'id' => $concertOrReharsal->id
            ];

            foreach ($this->participants as $participant) {
                $participantInfo = ['id' => $participant->id];

                if ($concertOrReharsal->participants->contains($participant)) {
                    $participant = $concertOrReharsal->participants->find($participant);

                    if ($participant->pivot->accepted && $participant->pivot->confirmed !== false) {
                        $participantInfo['accepted'] = true;
                    } else if ($participant->pivot->excused) {
                        $participantInfo['excused'] = true;
                    } else {
                        $participantInfo['accepted'] = false;
                    }
                } else {
                    $participantInfo['accepted'] = null;
                }

                $addToGrid['participants'][] = $participantInfo;
            }

            $grid[] = $addToGrid;

        }

        return $grid;
    }
}
