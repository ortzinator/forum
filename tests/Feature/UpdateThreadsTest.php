<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling();

        $this->signIn();
    }
    
    public function test_a_thread_may_be_updated_by_its_creator()
    {
        $thread = Thread::factory()->for(Auth::user())->create();

        $this->patchJson($thread->path(), [
            'title' => 'Changed Title',
            'body' => 'Changed Body'
        ]);

        $thread->refresh();

        $this->assertEquals('Changed Title', $thread->title);
        $this->assertEquals('Changed Body', $thread->body);
    }

    function test_a_thread_requires_a_title_and_body_to_be_updated()
    {
        $thread = Thread::factory()->for(Auth::user())->create();

        $this->patch($thread->path(), [
            'title' => 'Changed'
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed'
        ])->assertSessionHasErrors('title');
    }

    function test_unauthorized_users_may_not_update_threads()
    {
        $thread = Thread::factory()->for(User::factory()->create())->create();

        $this->patch($thread->path(), [])->assertStatus(403);
    }
}
