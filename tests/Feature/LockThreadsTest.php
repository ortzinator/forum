<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_once_locked_a_thread_may_not_recieve_new_replies()
    {
        $this->signIn();

        $thread = Thread::factory()->locked()->create();

        $this->post($thread->path() . '/replies', [
            'body' => 'FooBar',
            'user_id' => User::factory()->create()->id
        ])->assertStatus(422);
    }

    public function test_non_admins_may_not_lock_threads()
    {
        $this->signIn();

        $this->withExceptionHandling();

        $thread = Thread::factory()->for(Auth::user())->create();

        $this->post(route('locked-threads.store', $thread), [])->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    public function test_admins_can_lock_threads()
    {
        $this->signIn(User::factory()->admin()->create());

        $thread = Thread::factory()->for(Auth::user())->create();

        $this->post(route('locked-threads.store', $thread), []);

        $this->assertTrue($thread->fresh()->locked);
    }

    public function test_admins_can_unlock_threads()
    {
        $this->signIn(User::factory()->admin()->create());

        $thread = Thread::factory()->for(Auth::user())->locked()->create();

        $this->delete(route('locked-threads.destroy', $thread), []);

        $this->assertFalse($thread->fresh()->locked);
    }
}
