<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\Debugbar\Facade as Debugbar;
use Carbon\Carbon;

class Reply extends Model
{
    use HasFactory;
    use RecordsActivity;
    use Favoritable;

    protected $guarded = [];

    protected $with = ['user', 'favorites', 'thread'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    // protected $touches = ['thread'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->touch();
        });
    }

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

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([A-zÀ-ú_\-]*)/', $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([A-zÀ-ú_\-]*)/', '<a href="/profile/$1">$0</a>', $body);
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }
}
