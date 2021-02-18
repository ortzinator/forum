<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Request $request, Trending $trending)
    {
        if (request()->expectsJson()) {
            return Thread::search(request('q'))->paginate(25);
        }
        
        return view('threads.search', [
            'trending' => $trending->get()
        ]);
    }
}
