<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_thread_creator_may_mark_a_reply_as_best_answer()
    {
        $this->signIn();

        $thread = Thread::factory()->for(Auth::user())->create();

        $replies = Reply::factory()->count(2)->for($thread)->create();

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    public function test_only_thread_creator_may_mark_reply_as_best()
    {
        $this->signIn();

        $this->withExceptionHandling();

        $thread = Thread::factory()->for(Auth::user())->create();

        $replies = Reply::factory()->count(2)->for($thread)->create();

        $this->signIn(User::factory()->create());

        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
