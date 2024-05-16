<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\UserController;
use App\Models\LoanRequest;
use App\Models\TradeRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

//Users
Route::get('/users/search/form', [UserController::class, 'searchForm']);
Route::get('/users/search', [UserController::class, 'search']);
Route::get('users/search/proposers/{book}', [UserController::class, 'showProposers']);

//Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/users/user/books/create', [UserController::class, 'booksCreate']);

Route::get('/users/{user}', [UserController::class, 'show']);
Route::get('/users/{user}/books', [UserController::class, 'showBooks']);
Route::get('/users/{user}/books/{state}', [UserController::class, 'showBooks']);

Route::post('/users/{user}/books/{bookID}/{state}', [UserController::class, 'addBook']);
Route::delete('/users/{user}/books/{book}/{state}', [UserController::class, 'removeBook']);

//Books
Route::get('/books/search', [BookController::class, 'search']);

//Trades
Route::get('/trades/requests/received/{receiver}', [TradeRequestController::class, 'index'])
    ->middleware('auth')
    ->can('seePendingRequests', [TradeRequest::class, 'receiver']);

Route::get('/trades/ask/{receiver}/{requestedBook}', [TradeRequestController::class, 'show'])
    ->middleware('auth')
    ->can('requestBook', [TradeRequest::class, 'receiver']);

Route::post('/trades/propose/{proposedBook}', [TradeRequestController::class, 'store'])
    ->middleware('auth')
    ->can('proposeBook', [TradeRequest::class, 'proposedBook']);

Route::get('/trades/requests/accept/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [TradeRequest::class, 'sender', 'requestedBook', 'proposedBook']);

Route::get('/trades/requests/refuse/{sender}/{requestedBook}/{proposedBook}', [TradeRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [TradeRequest::class, 'sender', 'requestedBook', 'proposedBook']);

//Loans
Route::get('/loans/requests/received/{receiver}', [LoanRequestController::class, 'index'])
    ->middleware('auth')
    ->can('seePendingRequests', [LoanRequest::class, 'receiver']);

Route::get('/loans/requests/accept/{sender}/{requestedBook}', [LoanRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [LoanRequest::class, 'sender', 'requestedBook']);

Route::get('/loans/requests/refuse/{sender}/{requestedBook}', [LoanRequestController::class, 'update'])
    ->middleware('auth')
    ->can('resolveRequest', [LoanRequest::class, 'sender', 'requestedBook']);

