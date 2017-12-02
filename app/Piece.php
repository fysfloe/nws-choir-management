<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'composer',
        'year'
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
     * Get the concerts where this piece was sung.
     */
    public function concerts()
    {
        $this->belongsToMany('App\Concert');
    }

    public function voices()
    {
        $this->hasMany('App\Voice');
    }

    public function singers()
    {
        $this->hasManyThrough('App\User', 'App\Voice');
    }
}
