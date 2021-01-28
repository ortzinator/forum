<?php

namespace App\Models;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Thread extends Model
{
    use HasFactory;
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['user', 'channel'];

    protected $appends = ['isSubscribed'];

    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('replyCount', function(Builder $builder){ 
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->subscriptions->filter(function ($sub) use ($reply) {
            return $sub->user_id != $reply->user_id;
        })
            ->each->notify($reply);

        return $reply;
    }

    public function subscribe($userId = null)  
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: Auth::user()?->id
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: Auth::user()->id)
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', Auth::id())
            ->exists();
    }
}
