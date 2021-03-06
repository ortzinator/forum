<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ParticipateInThreadTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_auth_user_can_post_new_threads()
    {
        $this->signIn();

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    public function test_unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('threads/foobar/1/replies', [])
            ->assertRedirect('/login');
    }

    public function test_a_reply_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->makeOne(['body' => NULL]);

        $this->postJson($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

    public function test_unauth_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = Reply::factory()->create();

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect("/login");

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_auth_users_can_delete_reply()
    {
        $this->signIn();
        $userId = Auth::user()->id;
        $reply = Reply::factory()->create(['user_id' => $userId]);

        $this->delete("/replies/{$reply->id}");

        $findReply = Reply::find($reply->id);
        $this->assertNull($findReply);
    }

    public function test_auth_user_can_update_replies()
    {
        $this->signIn();
        $userId = Auth::user()->id;
        $reply = Reply::factory()->create(['user_id' => $userId]);

        $testBody = 'This is the new body';
        $this->patch("/replies/{$reply->id}", ['body' => $testBody]);

        $findReply = Reply::find($reply->id);
        $this->assertEquals($testBody, $findReply->body);
    }

    public function test_unauth_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = Reply::factory()->create();

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect("/login");

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_deleting_reply_doesnt_delete_thread()
    {
        $this->signIn();
        $userId = Auth::user()->id;
        $reply = Reply::factory()->create(['user_id' => $userId]);
        $threadId = $reply->thread->id;

        $this->delete("/replies/{$reply->id}");

        $findThread = Thread::find($threadId);
        $this->assertNotNull($findThread);
    }

    public function test_replies_that_contain_spam_may_not_be_created()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create([
            'body' => 'Yahoo Customer Support'
        ]);
        $cleanReply = Reply::factory()->create([
            'body' => "Yahoo! I'm Alive!"
        ]);
        $this->postJson($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);

        $this->postJson($thread->path() . '/replies', $cleanReply->toArray())
            ->assertStatus(201);
    }

    public function test_user_may_only_reply_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create([
            'body' => 'A reply'
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(201);

        $this->postJson($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }
}
