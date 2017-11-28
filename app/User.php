<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'surname', 'gender', 'birthdate', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the concerts for the user.
     */
    public function concerts()
    {
        return $this->belongsToMany('App\Concert');
    }

    /**
     * Get the concerts the user has created.
     */
    public function concertsCreated()
    {
        return $this->hasMany('App\Concert');
    }

    /**
     * Get the voices that the user sings.
     */
    public function voices()
    {
        return $this->belongsToMany('App\Voice');
    }
}
