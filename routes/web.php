<?php

use App\Http\Controllers\ChannelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UploadVideoController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CommentController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('channels', ChannelController::class);

Route::get('videos/{video}', [VideoController::class, 'show'])->name('videos.show');

Route::put('videos/{video}', [VideoController::class, 'updateViews']);

Route::put('videos/{video}/update', [VideoController::class, 'update'])->middleware(['auth'])->name('videos.update');

Route::get('videos/{video}/comments', [CommentController::class, 'index']);

Route::get('comments/{comment}/replies', [CommentController::class, 'show']);


Route::middleware(['auth'])->group(function () {

    Route::resource('channels/{channel}/subscriptions', SubscriptionController::class)->only(['store', 'destroy']);

    Route::post('channels/{channel}/videos', [UploadVideoController::class, 'store']);

    Route::get('channels/{channel}/videos', [UploadVideoController::class, 'index'])->name('channel.upload');

    Route::post('comments/{video}', [CommentController::class, 'store']);

    Route::post('votes/{entityId}/{type}', [VoteController::class, 'vote']);
});


