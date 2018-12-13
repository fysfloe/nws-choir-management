<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Concert extends Model
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
        'title', 'description', 'slug', 'date', 'start_time', 'end_time', 'created_by', 'semester_id', 'project_id', 'place'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the users for the concert.
     */
    public function participants()
    {
        return $this->belongsToMany('App\User', 'user_concert')
            ->withPivot('accepted', 'voice_id')
            ->withTimestamps();
    }

    /**
     * Get the user that created the concert.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the pieces for the concert.
     */
    public function pieces()
    {
        return $this->hasMany('App\Piece');
    }

    public function promises()
    {
        return $this->belongsToMany('App\User', 'user_concert')
            ->withPivot('accepted', 'voice_id')
            ->wherePivot('accepted', true);
    }

    public function denials()
    {
        return $this->belongsToMany('App\User', 'user_concert')
            ->wherePivot('accepted', false);
    }

    public function voices()
    {
        return $this->belongsToMany('App\Voice')
            ->withPivot('number')
            ->withTimestamps();
    }

    /**
     * Get the comments of the concert.
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

    public function voiceCount($voice_id)
    {
        return $this->promises()->wherePivot('voice_id', $voice_id)->count();
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function getDateTime(): \DateTime
    {
        $date = new \DateTime($this->date);
        $time = new \DateTime($this->start_time);
        $date->setTime($time->format('H'), $time->format('i'));

        return $date;
    }

    public static function getListForSelect()
    {
        $concerts = [];

        foreach (self::where('deleted_at', null)->get() as $concert) {
            $concerts[$concert->id] = $concert->title;
        }

        return $concerts;
    }
}
