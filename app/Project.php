<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
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
        'title', 'description', 'slug', 'created_by', 'semester_id', 'is_main', 'deadline'
    ];

    /**
     * Get the users for the project.
     */
    public function participants()
    {
        return $this->belongsToMany('App\User', 'user_project')
            ->withPivot('accepted', 'voice_id')
            ->withTimestamps();
    }

    /**
     * Get the user that created the project.
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the comments of the project.
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

    public function promises()
    {
        return $this->belongsToMany('App\User', 'user_project')
            ->withPivot('accepted', 'voice_id')
            ->wherePivot('accepted', true);
    }

    public function denials()
    {
        return $this->belongsToMany('App\User', 'user_project')
            ->wherePivot('accepted', false);
    }

    public function rehearsals()
    {
        return $this->hasMany('App\Rehearsal')
            ->orderBy('date', 'ASC');
    }

    public function concerts()
    {
        return $this->hasMany('App\Concert')
            ->orderBy('date', 'ASC');
    }

    public function voices()
    {
        return $this->belongsToMany('App\Voice')
            ->withPivot('number')
            ->withTimestamps();
    }

    public function voiceCount($voice_id)
    {
        return $this->promises()->wherePivot('voice_id', $voice_id)->count();
    }

    public function semester()
    {
        return $this->belongsTo('App\Semester');
    }

    public static function getListForSelect()
    {
        $projects = [];

        foreach (self::where('deleted_at', null)->get() as $project) {
            $projects[$project->id] = $project->title;
        }

        return $projects;
    }
}
