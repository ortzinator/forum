<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Reply;
use App\Models\Thread;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;
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
    
    /**
     * Persist a new reply
     * @param integer $channelId
     * @param Thread $thread
     * @param CreatePostRequest $form
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }
        
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => Auth::id()
        ])->load('user');
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
        try {
            request()->validate(['body' => ['required', new SpamFree]]);
        } catch (\Exception $th) {
            return response('Sorry, your reply could not be saved at this time', 422);
        }
        
        $reply->update(['body' => request('body')]);
    }
}
