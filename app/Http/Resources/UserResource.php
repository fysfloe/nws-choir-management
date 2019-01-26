<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource->voiceName) {
            $voice = ['name' => $this->resource->voiceName];
        } else {
            $voice = new VoiceResource($this->voice);
        }

        $user = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'voice' => $voice,
            'roles' => RoleResource::collection($this->roles),
            'avatar' => $this->avatar,
            'concerts' => $this->concerts,
            'rehearsals' => $this->rehearsals
        ];

        if ($this->resource->confirmed !== null) {
            $user['confirmed'] = $this->resource->confirmed;
        }

        if ($this->resource->excused !== null) {
            $user['excused'] = $this->resource->excused;
        }

        return $user;
    }
}
