<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAvatarController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image']
        ]);

        Auth::user()->update([
            'avatar_path' => $request->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
