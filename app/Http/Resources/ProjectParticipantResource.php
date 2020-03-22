<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

class ProjectParticipantResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $voice = ['name' => $this->voiceName, 'id' => $this->voiceId];
        $userId = $this->id;

        $user = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'voice' => $voice,
            'roles' => RoleResource::collection($this->roles),
            'avatar' => $this->avatar,
            'email' => $this->email,
            'voice_id' => $this->voice_id,
            'missed_rehearsals_count' => DB::table('rehearsals')
                ->distinct()
                ->leftJoin('user_rehearsal', function ($join) use ($userId) {
                    $join->on('user_rehearsal.rehearsal_id', '=', 'rehearsals.id')
                        ->where('user_rehearsal.user_id', $userId);
                })
                ->where(function ($query) {
                    $query->where('user_rehearsal.confirmed', false)
                        ->orWhereNull('user_rehearsal.user_id');
                })
                ->where('rehearsals.date', '<', (new \DateTime())->format('Y-m-d'))
                ->count()
        ];

        return $user;
    }
}
