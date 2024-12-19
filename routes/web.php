<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('chat');
})->middleware('auth');

Route::get('/getUserLogin', function () {
    return Auth::user();
})->middleware('auth');

Route::get('/messages', function () {
    return App\Models\Message::with('user')->get();
})->middleware('auth');

Route::post('/messages', function () {
    $user = Auth::user();

    $message = new App\Models\Message();
    $message->message = request()->get('message', '');
    $message->user_id = $user->id;
    $message->save();

    broadcast(new App\Events\MessagePosted($message, $user))->toOthers();
    return ['message' => $message->load('user')];
})->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
