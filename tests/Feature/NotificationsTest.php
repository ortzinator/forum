<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Database\Factories\DatabaseNotificationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_a_notif_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_that_is_not_by_the_current_user()
    {
        $thread = Thread::factory()->create()->subscribe();
        $this->assertCount(0, Auth::user()->notifications);

        $thread->addReply([
            'user_id' => Auth::id(),
            'body' => 'Some reply here'
        ]);
        
        $this->assertCount(0, Auth::user()->fresh()->notifications);
        
        $thread->addReply([
            'user_id' => 2,
            'body' => 'Some reply here'
            ]);

        $this->assertCount(1, Auth::user()->fresh()->notifications);
    }

    public function test_a_user_can_fetch_their_unread_notifs()
    {
        DatabaseNotificationFactory::new()->create();

        $response = $this->getJson("/profile/" . Auth::user()->name . "/notifications")->json();

        $this->assertCount(1, $response);
    }
    
    public function test_a_user_can_mark_notif_as_read()
    {
        DatabaseNotificationFactory::new()->create();

        $this->assertCount(1, Auth::user()->unreadNotifications);
        
        $notifId = Auth::user()->unreadNotifications->first()->id;
        
        $this->delete("/profile/" . Auth::user()->name . "/notifications/{$notifId}");
        $this->assertCount(0, Auth::user()->fresh()->unreadNotifications);
    }
}
