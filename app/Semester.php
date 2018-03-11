<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date', 'created_by'
    ];

    /**
     * Get the semesters projects.
     */
    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    /**
     * Get the semesters concerts.
     */
    public function concerts()
    {
        return $this->hasMany('App\Concert');
    }

    /**
     * Get the semesters rehearsals.
     */
    public function rehearsals()
    {
        return $this->hasMany('App\Rehearsal');
    }

    public function nextRehearsals()
    {
        $date = new \DateTime();

        return $this->rehearsals()
            ->where('date', '>=', $date)
            ->orderBy('date', 'ASC')
            ->limit(3)
            ->get();
    }

    /**
     * Get the users for the concert.
     */
    public function participants()
    {
        return $this->belongsToMany('App\User', 'user_semester')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    public function promises()
    {
        return $this->belongsToMany('App\User', 'user_semester')
            ->withPivot('accepted')
            ->wherePivot('accepted', true);
    }

    public function denials()
    {
        return $this->belongsToMany('App\User', 'user_semester')
            ->withPivot('accepted')
            ->wherePivot('accepted', false);
    }

    public static function current()
    {
        $date = new \DateTime();

        return self::where('start_date', '<=', $date)
            ->where('end_date', '>', $date)->first();
    }

    public function isCurrent()
    {
        return self::current() && $this->id === self::current()->id;
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function __toString()
    {
        $semesterString = '';
        if (date_format(date_create($this->start_date), 'Y') === date_format(date_create($this->end_date), 'Y')) {
            $semesterString .= date_format(date_create($this->start_date), 'd.m.');
        } else {
            $semesterString .= date_format(date_create($this->start_date), 'd.m.Y');
        }

        $semesterString .= ' – ' . date_format(date_create($this->end_date), 'd.m.Y');

        if ($this->isCurrent()) {
            $semesterString .= ' (' . __('Current') . ')';
        }

        return $semesterString;
    }

    public static function getListForSelect()
    {
        $semesters = [];

        foreach (self::where('deleted_at', null)->get() as $semester) {
            $semesters[$semester->id] = $semester->__toString();
        }

        return $semesters;
    }
}
