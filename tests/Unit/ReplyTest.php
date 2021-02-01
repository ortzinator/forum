<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;

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
}
