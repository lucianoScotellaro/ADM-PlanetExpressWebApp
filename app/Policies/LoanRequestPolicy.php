<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoanRequestPolicy
{
    public function seePendingRequests(User $user): bool
    {
        return Auth::user()->is($user);
    }

    public function resolveRequest(User $sender, Book $requestedBook): bool
    {
        $request = LoanRequest::find([Auth::id(), $sender->id, $requestedBook->ISBN])->with('receiver');
        return $request != null && $request->response == null && Auth::user()->is($request->receiver);
    }
}
