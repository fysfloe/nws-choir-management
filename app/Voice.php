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
        'name', 'slug', 'rank'
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
        return $this->hasMany('App\User', 'user_voice');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function pieces()
    {
        return $this->belongsToMany('App\Piece');
    }

    public function concerts()
    {
        return $this->belongsToMany('App\Concert')
            ->withPivot('number');
    }

    public static function getListForSelect()
    {
        $voices = [];

        foreach (self::where('deleted_at', null)->get() as $voice) {
            $voices[$voice->id] = $voice->name;
        }

        return $voices;
    }
}
