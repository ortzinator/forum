<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_records_activity_when_thread_is_created()
    {
        $this->signIn();
        $thread = Thread::factory()->create(['user_id' => Auth::id()]);

        $find = Activity::where([
            'type' => 'created_thread',
            'user_id' => $thread->user_id,
            'subject_id' => $thread->id,
            'subject_type' => 'App\Models\Thread'
        ])->first();

        $this->assertEquals($find->subject->id, $thread->id);
    }

    public function test_records_activity_when_a_reply_is_created()
    {
        $user = User::factory()->create(['id' => 555]);
        $this->actingAs($user);

        $reply = Reply::factory()->create(['user_id' => $user->id]);

        $this->assertEquals(2, Activity::count());
        $this->assertEquals($reply->user_id, $user->id);
    }
}
