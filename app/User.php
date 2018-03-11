<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait { restore as private restoreA; }
    use SoftDeletes { restore as private restoreB; }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'surname', 'gender', 'birthdate', 'email', 'password', 'country_id', 'phone', 'address_id', 'voice_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }

    /**
     * Get the concerts for the user.
     */
    public function concerts()
    {
        return $this->belongsToMany('App\Concert');
    }

    /**
     * Get the concerts for the user.
     */
    public function projects()
    {
        return $this->belongsToMany('App\Project');
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
     * Get the semesters the user has created.
     */
    public function semestersCreated()
    {
        return $this->hasMany('App\Semester', 'created_by');
    }

    /**
     * Get the rehearsals the user has created.
     */
    public function rehearsalsCreated()
    {
        return $this->hasMany('App\Rehearsal', 'created_by');
    }

    /**
     * Get the projects the user has created.
     */
    public function projectsCreated()
    {
        return $this->hasMany('App\Project', 'created_by');
    }

    /**
     * Get the voices that the user sings.
     */
    public function voices()
    {
        return $this->belongsToMany('App\Voice', 'user_voice')
            ->withTimestamps();
    }

    /**
     * Get the users primary voice.
     */
    public function voice()
    {
        return $this->belongsTo('App\Voice');
    }

    /**
     * Get the users citizenship.
     */
    public function citizenship()
    {
        return $this->belongsTo('Webpatser\Countries\Countries', 'country_id');
    }

    /**
     * Get the users address.
     */
    public function address()
    {
        return $this->belongsTo('App\Address');
    }
}
