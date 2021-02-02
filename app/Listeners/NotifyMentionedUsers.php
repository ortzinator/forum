<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Models\User;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        preg_match_all('/(?<=@)[^\s\.@]+/', $event->reply->body, $matches);

        foreach ($matches[0] as $name) {
            $user = User::whereName($name)->first();

            $user?->notify(new YouWereMentioned($event->reply));
        }
    }
}
