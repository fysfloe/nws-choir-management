<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug'
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
     * Get the singers for this voice.
     */
    public function singers()
    {
        return $this->hasMany('App\User');
    }

    public function pieces()
    {
        return $this->belongsToMany('App\Piece');
    }
}
