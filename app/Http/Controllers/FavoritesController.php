<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        if (! $reply->isFavorited()) {
            $reply->favorite();
        }
        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
