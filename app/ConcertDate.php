<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getDateAttribute()
    {
        return Carbon::parse($this->attributes['date']);
    }
}
