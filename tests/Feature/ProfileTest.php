<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_user_has_a_profile()
    {
        $user = User::factory()->create();
        $this->get("/profile/{$user->name}")
            ->assertSee($user->name);
    }

    public function test_shows_recent_activity()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $reply = Reply::factory()->create(['thread_id' => $thread->id]);
        $reply->favorite();

        $this->get("/profile/{$user->name}")
            ->assertSee($thread->title);
    }
}
