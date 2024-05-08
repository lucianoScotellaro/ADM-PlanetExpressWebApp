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
Route::get('/trades/requests/received/{user}', [TradeRequestController::class, 'index'])
    ->middleware('auth')
    ->can('seePendingRequests', [TradeRequest::class, 'user']);

Route::get('/trades/ask/{user}/{book}', [TradeRequestController::class, 'show'])
    ->middleware('auth')
    ->can('requestBook', [TradeRequest::class, 'user']);

Route::post('/trades/propose/{book}', [TradeRequestController::class, 'store'])
    ->middleware('auth')
    ->can('proposeBook', [TradeRequest::class, 'book']);

Route::get('/trades/requests/accept/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [TradeRequest::class, 'sender', 'requestedBook', 'proposedBook']);

Route::get('/trades/requests/refuse/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [TradeRequest::class, 'sender', 'requestedBook', 'proposedBook']);

//Loans
Route::get('/loans/requests/received/{user}', [LoanRequestController::class, 'index'])
    ->middleware('auth')
    ->can('seePendingRequests', [LoanRequest::class, 'user']);

Route::get('/loans/requests/accept/{sender}/{requestedBook}', [LoanRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [LoanRequest::class, 'sender', 'requestedBook']);

Route::get('/loans/requests/refuse/{sender}/{requestedBook}', [LoanRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [LoanRequest::class, 'sender', 'requestedBook']);

