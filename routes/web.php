<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\UserController;
use App\Models\LoanRequest;
use App\Models\TradeRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/user/books/create', [UserController::class, 'booksCreate']);

Route::get('/users/{user}/books', [UserController::class, 'showBooks']);
Route::get('/users/{user}/books/{state}', [UserController::class, 'showBooks']);

Route::post('/users/{user}/books/{book}/{state}', [UserController::class, 'addBook']);
Route::delete('/users/{user}/books/{book}/{state}', [UserController::class, 'removeBook']);

Route::get('/books/search', [BookController::class, 'search']);

//Trades
Route::get('/trades/requests/received/{receiver}', [TradeRequestController::class, 'index']);


Route::get('/trades/ask/{receiver}/{requestedBook}', [TradeRequestController::class, 'show']);


Route::post('/trades/propose/{proposedBook}', [TradeRequestController::class, 'store']);


Route::get('/trades/requests/accept/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);


Route::get('/trades/requests/refuse/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);


//Loans
Route::get('/loans/requests/received/{receiver}', [LoanRequestController::class, 'index']);

Route::get('/loans/requests/accept/{sender}/{requestedBook}', [LoanRequestController::class, 'update']);


Route::get('/loans/requests/refuse/{sender}/{requestedBook}', [LoanRequestController::class, 'update']);

