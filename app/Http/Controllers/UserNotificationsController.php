<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserNotificationsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function destroy(User $user, $notifId)
    {
        $user->notifications()->findOrFail($notifId)->markAsRead();
    }

    public function index()
    {
        return Auth::user()->unreadNotifications;
    }
}
