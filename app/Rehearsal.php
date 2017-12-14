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
        'date', 'created_by'
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

    public function promises()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->wherePivot('accepted', true);
    }

    public function denials()
    {
        return $this->belongsToMany('App\User', 'user_rehearsal')
            ->wherePivot('accepted', false);
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->attributes['date']);
    }
}
