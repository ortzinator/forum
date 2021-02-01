<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_fetch_their_most_recent_reply()
    {
        $user = User::factory()->create();

    	$recentReply = Reply::factory()->create([
    		'user_id' => $user->id,
    	]);

    	$olderReply = Reply::factory()->create([
    		'user_id' => $user->id,
    	]);

    	$olderReply->created_at = \Carbon\Carbon::now()->subMonth();

    	$this->assertEquals($recentReply->id, $user->lastReply->id);
    }
}
