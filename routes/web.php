<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/books', [UserController::class, 'showBooks']);

Route::get('/users/{user}/books/onloan', [UserController::class, 'showBooksOnLoan']);
Route::get('/users/{user}/books/onloan/create', [UserController::class, 'booksOnLoanCreate']);
Route::post('/users/{user}/books/{book}/onloan', [UserController::class, 'addBookOnLoan']);
Route::delete('/users/{user}/books/{book}/onloan', [UserController::class, 'removeBookOnLoan']);

Route::get('/books/search', [BookController::class, 'search']);
