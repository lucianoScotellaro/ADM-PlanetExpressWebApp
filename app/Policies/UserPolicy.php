<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Determine whether the user can add the book on his loans or not.
     */
    public function addBookOnLoan(User $user, Book $book): bool
    {
        if(Auth::user()->is($user) && !$book->users()->where('user_id', $user->id)->exists()){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can remove the book from his loans or not.
     */
    public function removeBookOnLoan(User $user, Book $book): bool
    {
        if(Auth::user()->is($user) && $book->users()->where('user_id', $user->id)->exists()){
            return true;
        }
        return false;
    }
}
