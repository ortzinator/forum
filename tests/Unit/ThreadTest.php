<?php

namespace Tests\Unit;

use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected Thread $thread;

    public function setup(): void
    {
        parent::setUp();

        $this->thread = Thread::factory()->create();
    }

    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_has_a_user()
    {
        $this->assertInstanceOf('App\Models\User', $this->thread->user);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = Thread::factory()->create();
        $userId = 1;

        $thread->subscribe($userId);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count()); 
    }

    public function test_a_user_can_unsub_from_thread()
    {
        $thread = Thread::factory()->create();
        $userId = 1;

        $thread->subscribe($userId);
        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions); 
    }

    public function test_knows_if_auth_user_is_subbed()
    {
        $thread = Thread::factory()->create();

        $this->signIn();

        $this->assertFalse($thread->isSubscribed);

        $thread->subscribe();
        
        $this->assertTrue($thread->isSubscribed);
    }
}
