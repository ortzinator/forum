<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        $search = 'Foobar';

        Thread::factory()->count(2)->create();
        Thread::factory()->count(2)->state(['body' => "A thread with the {$search} term"])->create();

        do {
            sleep(0.25);
            $results = $this->getJson("/threads/search?q={$search}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        Thread::latest()->take(4)->unsearchable();
    }
}
