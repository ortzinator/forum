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

        $reply = Reply::factory()->make([
            'body' => '@JaneDoe look at this @Goober'
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->fresh()->notifications);
    }

    public function test_it_can_search_mentioned_users()
    {
        User::factory()->create(['name' => 'JohnDoe']);
        User::factory()->create(['name' => 'JohnDoe2']);
        User::factory()->create(['name' => 'JaneDoe']);

        $results = $this->getJson('/api/users?name=john');

        $this->assertCount(2, $results->json());
    }
}
