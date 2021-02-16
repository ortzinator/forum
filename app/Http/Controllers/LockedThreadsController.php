<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
