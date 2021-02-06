<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Thread;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        if (!$user->exists && Auth::check()) {
            $user = Auth::user();
        }
        else {
            return redirect('/login');
        }

        return view('profiles.show')
            ->with(['profileUser' => $user])
            ->with(['activities' => Activity::feed($user)]);
    }
}
