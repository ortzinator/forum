<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }
}
