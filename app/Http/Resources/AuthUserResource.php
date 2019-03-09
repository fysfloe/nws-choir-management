<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AuthUserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'canManageRehearsals' => $this->can('manageRehearsals'),
            'canManageProjects' => $this->can('manageProjects'),
            'canManageConcerts' => $this->can('manageConcerts'),
            'canManageSemesters' => $this->can('manageSemesters'),
            'canManageUsers' => $this->can('manageUsers'),
        ];
    }
}
