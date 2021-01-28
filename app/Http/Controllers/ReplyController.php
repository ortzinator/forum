<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except('index');
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }
    
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);
        
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth::id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('user');
        }

        return redirect($thread->path());
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();
        
        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back()->with('flash', 'The reply was deleted');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->update(['body' => request('body')]);
    }
}
