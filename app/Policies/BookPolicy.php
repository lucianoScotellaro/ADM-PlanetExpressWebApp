<?php

namespace App\Policies;

use App\Models\User;

class BookPolicy
{
    public function addBook(User $authenticated, User $user): bool
    {
        return $authenticated->is($user) ;
    }

    public function removeBook(User $authenticated, User $user): bool
    {
        return $authenticated->is($user);
    }
}
