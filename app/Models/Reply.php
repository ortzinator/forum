<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\Debugbar\Facade as Debugbar;

class Reply extends Model
{
    use HasFactory;
    use RecordsActivity;
    use Favoritable;

    protected $guarded = [];

    protected $with = ['user', 'favorites', 'thread'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    /**
     * A reply has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
