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
            $voice = ['name' => $this->resource->voiceName, 'id' => $this->resource->voiceId];
        } else {
            $voice = new VoiceResource($this->voice);
        }

        $user = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'username' => $this->username,
            'voice' => $voice,
            'roles' => RoleResource::collection($this->roles),
            'avatar' => $this->avatar,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'country_id' => $this->country_id,
            'address' => $this->address ? new AddressResource($this->address) : [
                'street' => null,
                'zip' => null,
                'city' => null,
                'country_id' => null
            ],
            'non_singing' => $this->non_singing,
            'voice_id' => $this->voice_id
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
