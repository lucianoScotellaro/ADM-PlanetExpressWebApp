<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Users
Route::get('/users/{user}/books', [UserController::class, 'showBooks']);

//Books
Route::get('/books/{book}', [BookController::class, 'show']);

//Trades
Route::get('/trades/requests/received/{user}', [TradeRequestController::class, 'index']);
Route::get('/trades/ask/{user}/{book}', [TradeRequestController::class, 'show']);
Route::post('/trades/propose/{book}', [TradeRequestController::class, 'store']);
Route::get('/trades/requests/accept/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);
Route::get('/trades/requests/refuse/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);
