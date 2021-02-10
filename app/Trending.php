<?php 

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending {

    private $key = 'trending_threads';

    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->key, 0, 5));
    }

    public function push($thread)
    {
        Redis::zincrby($this->key, 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->key);
    }
}