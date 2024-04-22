<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showBooks(User $user){
       return view('users.show-books', ['user' => $user, 'books'=>$user->books]);
    }
}
