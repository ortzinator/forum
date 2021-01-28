<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        static::created(function ($thread) {
            $thread->recordActivity('created');
        });

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    protected function recordActivity($event)
    {
        if (Auth::guest()) return;
        $this->activity()->create([
            'user_id' => Auth::id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Models\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        return 'created_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}