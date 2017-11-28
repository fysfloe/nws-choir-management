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
        'composer',
        'year'
    ];

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
