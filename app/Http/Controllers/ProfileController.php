<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Thread;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        // return $activities;

        return view('profiles.show')
            ->with(['profileUser' => $user])
            ->with(['activities' => Activity::feed($user)]);
    }
}
