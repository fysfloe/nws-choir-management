<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rehearsal extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'start_time', 'end_time', 'place', 'created_by', 'semester_id', 'project_id', 'description', 'deadline'
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

    /**
     * Get the users for the rehearsal.
     */
    public function participants()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->withPivot(['accepted', 'confirmed', 'excused'])
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

    /**
     * Get the comments of the rehearsal.
     */
    public function comments()
    {
        if (Auth::user()->hasRole('admin')) {
            return $this->morphMany('App\Comment', 'commentable')->orderBy('created_at', 'DESC');
        } else {
            return $this->morphMany('App\Comment', 'commentable')
                ->where('private', '=',  false)
                ->orWhere('user_id', Auth::user()->id)
                ->where('commentable_id', '=', $this->id)
                ->where('commentable_type', '=', self::class)
                ->orderBy('created_at', 'DESC');
        }
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

    /**
     * @return string
     */
    public function spreadSheetHeading(): string
    {
        return __('Rehearsal') . ' (' . $this->date->format('d.m.Y') . ')';
    }

    public function title()
    {
        return __('Rehearsal') . ': ' . $this->date->format('d.m.Y');
    }

    public function getDateTime(): \DateTime
    {
        $dateTime = new \DateTime($this->date);
        $startTime = new \DateTime($this->start_time);
        $dateTime->setTime($startTime->format('H'), $startTime->format('i'));

        return $dateTime;
    }
}
