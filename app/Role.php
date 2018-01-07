<?php

namespace App;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\Model;

class Role extends EntrustRole
{
    public static function getListForSelect()
    {
        $roles = [];

        foreach (self::all() as $role) {
            $roles[$role->id] = $role->display_name;
        }

        return $roles;
    }
}
