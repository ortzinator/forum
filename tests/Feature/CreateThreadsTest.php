<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_authenticated_user_can_create_thread()
    {
        $this->signIn();
        
        $thread = Thread::factory()->make();

        $response = $this->post(route('threads.store'), $thread->toArray());
        
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->body);
    }

    // function test_a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    // {
    //     $this->signIn();

    //     $thread = Thread::factory()->create(['title' => 'Some Title 24']);

    //     $thread = $this->postJson(route('threads.store'), $thread->toArray())->json();
    //     dd($thread);

    //     $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
    // }

    public function test_guest_cannot_create_thread()
    {
        $this->withExceptionHandling();
        $this->post(route('threads.store'))
            ->assertRedirect('/login');

        $this->get(route('threads.create'))
            ->assertRedirect('/login');
    }

    public function test_a_thread_must_have_title()
    {
        $this->signIn();

        $this->publishThread(['title' => NULL])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_valid_channel()
    {
        Channel::factory(2)->create();
        
        $this->publishThread(['channel_id' => NULL])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function test_auth_users_must_first_verify_email_before_creating_thread()
    {
        $this->publishThread([], ['email_verified_at' => NULL])
            ->assertRedirect('/verify-email');
    }

    public function publishThread($threadOverrides = [], $userOverrides = [])
    {
        $this->withExceptionHandling()->signIn($userOverrides);

        $thread = Thread::factory()->makeOne($threadOverrides);
        return $this->post(route('threads.store'), $thread->toArray());
    }
}
