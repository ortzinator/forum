<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'BrianOrtiz',
            'email' => 'ortzinator@gmail.com'
        ]);
        User::factory()->create([
            'name' => 'JaneDoe',
            'email' => 'foo@bar.com'
        ]);
        $channels = Channel::factory()->count(5)->create();

        $channels->each(function ($channel) {
            \App\Models\Thread::factory()->count(10)->create(['channel_id' => $channel->id]);
        });

        Thread::all()->each(function($thread) {
            \App\Models\Reply::factory()->count(10)->create(['thread_id'=> $thread->id]);
        });
    }
}
