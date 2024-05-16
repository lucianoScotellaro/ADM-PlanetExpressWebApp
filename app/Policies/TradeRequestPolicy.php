<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;

class TradeRequestPolicy
{
    public function requestBook(User $user, User $receiver, Book $requestedBook):bool
    {
        return !$user->is($receiver) && $receiver->books->contains($requestedBook);
    }

    public function proposeBook(User $user, Book $proposedBook):bool
    {
        return $user->books->contains($proposedBook);
    }

    public function resolveRequest(User $user, User $sender, Book $requestedBook, Book $proposedBook):bool
    {
        $request = TradeRequest::find([$sender->id, $user->id, $proposedBook->id, $requestedBook->id]);
        return $request != null && $request->response === null;
    }
}
