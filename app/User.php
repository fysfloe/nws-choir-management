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
        'firstname', 'surname', 'gender', 'birthdate', 'email', 'password', 'country_id'
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
     * Get the rehearsals for the user.
     */
    public function rehearsals()
    {
        return $this->belongsToMany('App\Rehearsal', 'user_rehearsal')->withTimestamps();
    }

    /**
     * Get the concerts the user has created.
     */
    public function concertsCreated()
    {
        return $this->hasMany('App\Concert', 'created_by');
    }

    /**
     * Get the concerts the user has created.
     */
    public function rehearsalsCreated()
    {
        return $this->hasMany('App\Rehearsal', 'created_by');
    }

    /**
     * Get the voices that the user sings.
     */
    public function voices()
    {
        return $this->belongsToMany('App\Voice', 'user_voice');
    }

    /**
     * Get the users citizenship.
     */
    public function citizenship()
    {
        return $this->belongsTo('Webpatser\Countries');
    }
}
