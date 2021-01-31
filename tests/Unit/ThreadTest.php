<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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

    public function test_thread_can_add_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $userId = 1;

        $this->thread->subscribe($userId);

        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', $userId)->count()); 
    }

    public function test_a_user_can_unsub_from_thread()
    {
        $userId = 1;

        $this->thread->subscribe($userId);
        $this->thread->unsubscribe($userId);

        $this->assertCount(0, $this->thread->subscriptions); 
    }

    public function test_knows_if_auth_user_is_subbed()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribed);

        $this->thread->subscribe();
        
        $this->assertTrue($this->thread->isSubscribed);
    }
    
    public function test_a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn();

        $this->thread->subscribe();

        $this->thread->addReply([
            'user_id' => 999,
            'body' => 'Some reply here'
        ]);

        Notification::assertSentTo(Auth::user(), ThreadWasUpdated::class);
    }

    public function test_a_thread_can_check_if_the_auth_user_has_read_all_replies()
    {
        $this->signIn();

        $user = Auth::user();

        $this->assertTrue($this->thread->hasUpdatesFor($user));

        $this->goForwardADay();

        $user->read($this->thread);

        $this->assertFalse($this->thread->hasUpdatesFor($user));
    }

    public function test_leaving_a_reply_marks_thread_as_unread()
    {
        $this->signIn();

        $user = Auth::user();

        $user->read($this->thread);

        $this->goForwardADay();

        $this->thread->addReply([
            'user_id' => 999,
            'body' => 'Some reply here'
        ]);

        $this->thread = $this->thread->fresh();

        $this->assertTrue($this->thread->hasUpdatesFor($user));
    }

    public function test_adding_a_reply_updates_the_thread_updatedat_field()
    {
        $one = $this->thread->updated_at;

        $this->goForwardADay();

        $this->thread->addReply([
            'user_id' => 999,
            'body' => 'Some reply here'
        ]);

        $two = $this->thread->fresh()->updated_at;

        $this->assertNotTrue($one->equalTo($two));
    }
}
