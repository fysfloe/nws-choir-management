<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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

        $user = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'voice' => $voice,
            'roles' => RoleResource::collection($this->roles),
            'avatar' => $this->avatar,
            'email' => $this->email,
            'voice_id' => $this->voice_id,
            'missed_rehearsals_count' => $this->missed_rehearsals_count
        ];

        return $user;
    }
}
