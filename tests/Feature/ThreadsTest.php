<?php

namespace Tests\Feature;

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\Channel;
use App\Models\User;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        //$this->thread = Thread::factory()->create();
    }
    
    public function test_user_can_view_threads()
    {
        $thread = Thread::factory()->create();
        $this->get('/threads')
            ->assertSee($thread->title);
    }

    public function test_user_can_view_a_thread()
    {
        $thread = Thread::factory()->create();
        $this->get($thread->path())
            ->assertOk()
            ->assertSee($thread->title);
    }

    public function test_user_can_view_thread_replies()
    {
        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create(['thread_id' => $thread->id]);

        $this->get($thread->path())
            ->assertOk()
            ->assertSee($reply->body);
    }

    public function test_thread_author_is_visible()
    {
        $thread = Thread::factory()->create();
        $this->get($thread->path())
            ->assertSee($thread->user->name);
    }

    public function test_a_user_can_filter_threads_by_channel()
    {
        $channel = Channel::factory()->create();
        $threadInChannel = Thread::factory()->create(['channel_id' => $channel->id]);
        $threadNotInChannel = Thread::factory()->create();

        $this->get('threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_user_can_filter_threads_by_username()
    {
        $this->signIn(User::factory()->state(['name' => 'JohnDoe'])->create());

        $threadByJohn = Thread::factory()->create(['user_id' => Auth::user()->id]);
        $threadNotByJohn = Thread::factory()->create();

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    public function test_a_user_can_sort_threads_by_reply_count()
    {
        $threadWithOneReply = Thread::factory()
            ->hasReplies(1)
            ->create();

        $threadWithThreeReplies = Thread::factory()
            ->hasReplies(3)
            ->create();
        
        $threadWithZeroReplies = Thread::factory()->create();            

        $this->getJson('threads?popular=1')
            ->assertSeeInOrder([
                $threadWithThreeReplies->title,
                $threadWithOneReply->title,
                $threadWithZeroReplies->title
            ]);
    }

    public function test_auth_users_can_delete_a_thread()
    {
        $userId = 42;
        $this->signIn(User::factory()->state(['id' => $userId])->create());
        $thread = Thread::factory()->create(['user_id' => $userId]);
        $reply = Reply::factory()->create(['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
            ->assertStatus(204);
        $findThread = Thread::find($thread->id);
        $findReply = Reply::find($reply->id);
        $findThreadActivity = Activity::where([
            'subject_id' => $thread->id,
            'subject_type' => Thread::class
        ])->get();
        $findReplyActivity = Activity::where([
            'subject_id' => $reply->id,
            'subject_type' => Reply::class
        ])->get();
        // dd($findThreadActivity->toArray());

        $this->assertNull($findThread);
        $this->assertNull($findReply);
        $this->assertEmpty($findThreadActivity->toArray());
        $this->assertEmpty($findReplyActivity->toArray());
    }

    public function test_unauth_user_cannot_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = Thread::factory()->create();

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }

    public function test_a_user_can_request_all_replies_for_a_given_thread()
    {
        $reply_total = 10;
        $thread = Thread::factory()->create();
        Reply::factory($reply_total)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path() . '/replies')->json();
        
        $this->assertCount(5, $response['data']); //This is the number of Replies on a page
        $this->assertEquals($reply_total, $response['total']);
    }
    
    public function test_can_view_unanswered_threads()
    {
        $thread = Thread::factory()->create();
        $thread2 = Thread::factory()->create();
        Reply::factory(2)->create(['thread_id' => $thread->id]);
        
        $response = $this->getJson('/threads?unanswered=1')->json();
        // dd($response);
        $this->assertCount(1, $response);
    }
}
