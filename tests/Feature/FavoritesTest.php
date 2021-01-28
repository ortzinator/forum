<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_favorite_a_reply()
    {
        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }
    
    public function test_an_auth_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = Reply::factory()->create();

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    public function test_an_auth_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = Reply::factory()->create();

        $this->post('replies/' . $reply->id . '/favorites');

        $this->delete('replies/' . $reply->id . '/favorites');
        
        // $reply->update();

        $this->assertCount(0, $reply->favorites);
    }

    public function test_an_auth_user_can_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = Reply::factory()->create();

        $this->post('replies/' . $reply->id . '/favorites');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Illuminate\Database\QueryException $th) {
            $this->fail('Did not expect to insert the same record twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }

    public function test_reply_user_favorited()
    {
        $this->withoutExceptionHandling();
        $guestReply = Reply::factory()->create();
        $this->assertFalse($guestReply->isFavorited(Auth::id()));
        
        $this->signIn();
        $reply = Reply::factory()->create();
        $this->assertFalse($reply->isFavorited(Auth::id()));

        $reply->favorite();
        $reply->refresh();
        $this->assertTrue($reply->isFavorited(Auth::id()));
    }
}
