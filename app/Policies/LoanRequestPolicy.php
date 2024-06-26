<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoanRequestPolicy
{
    public function requestBook(User $user, User $receiver, Book $requestedBook):bool
    {
        return !$user->is($receiver) && $receiver->books->contains($requestedBook);
    }
    public function resolveRequest(User $user, User $sender, Book $requestedBook): bool
    {
        $request = LoanRequest::find([$user->id, $sender->id, $requestedBook->id]);
        return $request != null && $request->response === null;
    }
}
