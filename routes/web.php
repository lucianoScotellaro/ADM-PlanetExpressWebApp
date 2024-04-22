<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/books', [UserController::class, 'showBooks']);

Route::get('/trades', [TradeRequestController::class, 'index']);
Route::post('/trades/{book}', [TradeRequestController::class, 'store']);
Route::get('/trades/{user}/{book}', [TradeRequestController::class, 'show']);

Route::get('/books/{book}', [BookController::class, 'show']);
