<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_only_members_can_add_avatars()
    {
        $this->withExceptionHandling();
        $this->postJson('/api/users/1/avatar')
            ->assertStatus(401);
    }

    public function test_a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->signIn();

        $this->postJson('/api/users/' . Auth::id() . '/avatar', [
            'avatar' => 'not an image'
        ])->assertStatus(422);
    }

    public function test_a_user_may_add_an_avatar()
    {
        $this->withExceptionHandling()->signIn();

        Storage::fake('public');

        $this->postJson('/api/users/' . Auth::id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])->assertStatus(204);

        $this->assertEquals(Storage::url('avatars/'.$file->hashName()), Auth::user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
