<?php

use App\Http\Controllers\BestRepliesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadSubscriptionController;
use App\Http\Controllers\UserNotificationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/threads');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

//Threads
Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/threads/{channel}', [ThreadController::class, 'index'])->name('threads.index.channel');
Route::get('/threads/{channel}/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
Route::delete('/threads/{channel}/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');

//Thread subscriptions
Route::post('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionController::class, 'store'])->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionController::class, 'destroy'])->middleware('auth');

//Replies
Route::get('/threads/{channel}/{thread}/replies', [ReplyController::class, 'index']);
Route::post('/threads/{channel}/{thread}/replies', [ReplyController::class, 'store']);
Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');
Route::patch('/replies/{reply}', [ReplyController::class, 'update']);

Route::post('/replies/{reply}/best', [BestRepliesController::class, 'store'])->name('best-replies.store');

//Favorites
Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);
Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy']);

//Users
Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile');
Route::get('/api/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
Route::post('/api/users/{user}/avatar', [\App\Http\Controllers\Api\UserAvatarController::class, 'store'])->name('avatar');

//Notifications
Route::delete('/profile/{user}/notifications/{notification}', [UserNotificationsController::class, 'destroy']);
Route::get('/profile/{user}/notifications', [UserNotificationsController::class, 'index']);