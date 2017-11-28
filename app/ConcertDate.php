<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConcertDate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date'
    ];

    /**
     * Get the concert that owns the date.
     */
    public function concert()
    {
        return $this->belongsTo('App\Concert');
    }
}
