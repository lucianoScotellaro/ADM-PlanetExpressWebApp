<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TradeRequestPolicy
{
    public function seePendingRequests(User $user, User $receiver):bool
    {
        return $user->is($receiver);
    }

    public function requestBook(User $user, User $receiver):bool
    {
        return !$user->is($receiver);
    }

    public function proposeBook(User $user, Book $proposedBook):bool
    {
        return $user->books->contains($proposedBook);
    }

    public function resolveRequest(User $user, User $sender, Book $requestedBook, Book $proposedBook):bool
    {
        $request = TradeRequest::find([$sender->id, $user->id, $proposedBook->ISBN, $requestedBook->ISBN]);
        return $request != null && $request->response === null;
    }
}
