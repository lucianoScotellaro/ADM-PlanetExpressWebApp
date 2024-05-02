<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/user/books/create', [UserController::class, 'booksCreate']);

Route::get('users/{user}/books', [UserController::class, 'showBooks']);
Route::get('/users/{user}/books/{state}', [UserController::class, 'showBooks']);

Route::post('/users/{user}/books/{book}/{state}', [UserController::class, 'addBook']);
Route::delete('/users/{user}/books/{book}/{state}', [UserController::class, 'removeBook']);

Route::get('/books/search', [BookController::class, 'search']);
