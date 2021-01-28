<?php

namespace Database\Seeders;

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
        $threads = \App\Models\Thread::factory()->count(50)->create();
        $threads->each(function($thread) { \App\Models\Reply::factory()->count(10)->create(['thread_id'=> $thread->id]);  });
    }
}
