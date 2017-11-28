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
        'title'
    ];

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
        return $this->belongsTo('App\User');
    }

    /**
     * Get the pieces for the concert.
     */
    public function pieces()
    {
        return $this->hasMany('App\Piece');
    }
}
