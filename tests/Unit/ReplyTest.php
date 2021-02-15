<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;

use function PHPUnit\Framework\assertEquals;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_user()
    {
        $reply = Reply::factory()->create();

        $this->assertInstanceOf('App\Models\User', $reply->user);
    }

    public function test_it_knows_if_it_was_just_published()
    {
        $reply = Reply::factory()->create();

        $this->assertTrue($reply->wasJustPublished());
        
        $reply->created_at = Carbon::now()->subMonth();
        
        $this->assertFalse($reply->wasJustPublished());
    }

    public function test_it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new Reply(['body' => '@JaneDoe want to talk to @JohnDoe']);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    public function test_it_wraps_mentioned_usernames_in_the_body_within_a_tags()
    {
        $reply = new Reply(['body' => 'Hello @JaneDoe.']);

        $this->assertEquals('Hello <a href="/profile/JaneDoe">@JaneDoe</a>.', $reply->body);
    }
    
    public function test_it_knows_it_is_the_best_reply()
    {
        $reply = Reply::factory()->create();

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
