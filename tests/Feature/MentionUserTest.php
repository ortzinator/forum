<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentioned_users_in_a_reply_are_notified()
    {
        $john = User::factory()->make(['name' => 'JohnDoe']);

        $this->signIn($john->toArray());

        $jane = User::factory()->create(['name' => 'JaneDoe']);

        $thread = Thread::factory()->create();

        $reply = Reply::factory()->create([
            'body' => '@JaneDoe look at this @Goober'
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);

    }
}
