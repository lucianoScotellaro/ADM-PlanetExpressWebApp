<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

//Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/users/user/books/create', [UserController::class, 'booksCreate']);

Route::get('users/{user}/books', [UserController::class, 'showBooks']);
Route::get('/users/{user}/books/{state}', [UserController::class, 'showBooks']);

Route::post('/users/{user}/books/{book}/{state}', [UserController::class, 'addBook']);
Route::delete('/users/{user}/books/{book}/{state}', [UserController::class, 'removeBook']);

Route::get('/books/search', [BookController::class, 'search']);

//Trades
Route::get('/trades/requests/received/{user}', [TradeRequestController::class, 'index']);
Route::get('/trades/ask/{user}/{book}', [TradeRequestController::class, 'show']);
Route::post('/trades/propose/{book}', [TradeRequestController::class, 'store']);
Route::get('/trades/requests/accept/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);
Route::get('/trades/requests/refuse/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);
