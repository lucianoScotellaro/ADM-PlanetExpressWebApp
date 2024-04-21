<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/books', function(User $user){
   return view('users.show-books', ['user'=>$user,'books'=>$user->books]);
});
