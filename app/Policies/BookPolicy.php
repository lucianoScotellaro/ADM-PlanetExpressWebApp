<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookPolicy
{
    /**
     * Determine whether the user can add the book on his loans or not.
     */

    public function editBooks(User $logged, User $user):bool
    {
        return $logged->is($user);
    }
}
