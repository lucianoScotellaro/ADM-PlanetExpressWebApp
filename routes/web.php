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

//Trades
Route::get('/trades/requests/received/{user}', [TradeRequestController::class, 'index']);
Route::get('/trades/ask/{user}/{book}', [TradeRequestController::class, 'show']);
Route::post('/trades/propose/{book}', [TradeRequestController::class, 'store']);
Route::get('/trades/requests/accept/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);
Route::get('/trades/requests/refuse/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update']);

Route::get('/users/{user}/books/onloan', [UserController::class, 'showBooksOnLoan'])->middleware('auth');
Route::get('/users/user/books/onloan/create', [UserController::class, 'booksOnLoanCreate'])->middleware('auth');
Route::post('/users/{user}/books/{book}/onloan', [UserController::class, 'addBookOnLoan'])->middleware('auth')->can('addBookOnLoan', 'book');
Route::delete('/users/{user}/books/{book}/onloan', [UserController::class, 'removeBookOnLoan'])->middleware('auth')->can('removeBookOnLoan',  'book');

Route::get('/books/search', [BookController::class, 'search']);
