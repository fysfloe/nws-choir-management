<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'title', 'description', 'slug', 'created_by'
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
        return $this->hasMany('App\ConcertDate')
            ->orderBy('date');
    }

    public function nextDate()
    {
        return $this->hasMany('App\ConcertDate')
            ->where('date', '>=', new \DateTime())
            ->orderBy('date')
            ->first();
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
