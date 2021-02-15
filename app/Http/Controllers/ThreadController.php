<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use App\Rules\SpamFree;
use App\Trending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ThreadController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'verified'])->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, Request $request, Trending $trending)
    {
        if ($channel->exists) {
            $threads = $channel->threads()->latest(); //TODO: Pagination
        } else {
            $threads = Thread::latest();
        }

        if ($request->boolean('unanswered')) {
            $threads = $threads->doesntHave('replies');
        }

        if ($username = $request->query('by')) {
            $user = User::where('name', $username)->firstOrFail();
            $threads->where('user_id', $user->id);
        }

        if ($request->boolean('popular')) {
            $threads = $threads->orderBy('replies_count', 'desc');
        }

        if (request()->wantsJson()) {
            $threads = $threads->get();
            return $threads;
        }

        $threads = $threads->paginate(20);

        return view('threads.index')
            ->with('threads', $threads)
            ->with('trending', $trending->get())
            ->with('channelName', $channel->name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => Auth::id(),
            'channel_id' => $request['channel_id'],
            'title' => $request['title'],
            'body' => $request['body']
            ]);

        if ($request->expectsJson()) {
            return response($thread);
        }
            
        return redirect($thread->path())
            ->with('flash', __('Your thread was published'));
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $channelId
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        if (Auth::check()) {
            Auth::user()->read($thread);
        }
        
        $trending->push($thread);
        $thread->increment('visits');

        return view('threads.show')->with(['thread' => $thread]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        return response([], 204);
    }
}
