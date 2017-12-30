<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street', 'zip', 'city', 'country_id'
    ];

    public function country()
    {
        return $this->belongsTo('Webpatser\Countries\Countries');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
