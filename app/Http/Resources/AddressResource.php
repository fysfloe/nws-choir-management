<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AddressResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country_id' => $this->country_id
        ];
    }
}
