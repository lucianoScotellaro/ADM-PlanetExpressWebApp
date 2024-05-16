<?php

namespace App\Policies;

use App\Models\User;

class BookPolicy
{
    public function editBooks(User $authenticated, User $user):bool
    {
        return $authenticated->is($user);
    }
}
