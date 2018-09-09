<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Rehearsal extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'start_time', 'end_time', 'place', 'created_by', 'semester_id', 'project_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user that created the rehearsal.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    /**
     * Get the users for the rehearsal.
     */
    public function participants()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    /**
     * Get the users that accepted the rehearsal.
     */
    public function promises()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->wherePivot('accepted', true);
    }

    /**
     * Get the users that didn't accept the rehearsal.
     */
    public function denials()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->wherePivot('accepted', false);
    }

    public function confirmed()
    {
        return $this->promises()
            ->wherePivot('confirmed', true);
    }

    /**
     * Get the users that accepted the rehearsal but didn't show up unexcused.
     */
    public function unexcused()
    {
        return $this->promises()
            ->wherePivot('confirmed', false)
            ->wherePivot('excused', false);
    }

    /**
     * Get the users that accepted the rehearsal but didn't show up excused.
     */
    public function excused()
    {
        return $this->promises()
            ->withPivot('excuse')
            ->wherePivot('excused', true);
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->attributes['date']);
    }

    public function semester()
    {
        return $this->belongsTo('App\Semester');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function __toString()
    {
        $dateString = "<span class='oi oi-calendar text-muted'></span>&nbsp;" . $this->date->format('d.m.Y') . "&nbsp;";
        $dateString .= "<span class='oi oi-clock text-muted'></span>&nbsp;" . date_format(date_create($this->start_time), 'H:i') . '–' . date_format(date_create($this->end_time), 'H:i');

        return $dateString;
    }

    public function twoLineString()
    {
        $dateString = "<span class='oi oi-calendar text-muted'></span>&nbsp;" . $this->date->format('d.m.Y') . "<br>";
        $dateString .= "<span class='oi oi-clock text-muted'></span>&nbsp;" . date_format(date_create($this->start_time), 'H:i') . '–' . date_format(date_create($this->end_time), 'H:i');

        return $dateString;
    }
}
