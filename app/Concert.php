<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'created_by'
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
     * Get the dates for the concert.
     */
    public function dates()
    {
        return $this->hasMany('App\ConcertDate');
    }

    /**
     * Get the users for the concert.
     */
    public function participants()
    {
        return $this->hasMany('App\User');
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
}
