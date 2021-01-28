<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

/**
 * 
 */
trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        return $this->favorites()->create(['user_id' => Auth::id()]);
    }

    public function unfavorite()
    {
        return $this->favorites()
            ->where(['user_id' => Auth::id()])->get()->each->delete();
    }

    public function isFavorited()
    {
        if (Auth::guest()) { return false; }
        return !! $this->favorites->where('user_id', Auth::user()->id)->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
