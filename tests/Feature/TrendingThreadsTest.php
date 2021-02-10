<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending();
        $this->trending->reset();
    }

    public function test_it_increments_a_threads_score_each_time_it_is_read()
    {
        $thread = Thread::factory()->create();
        $this->call('GET', $thread->path());

        $trending = $this->trending->get($thread);

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
