<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TradeRequestPolicy
{
    public function seePendingRequests(User $user):bool
    {
        return Auth::user()->is($user);
    }

    public function requestBook(User $user):bool
    {
        return !Auth::user()->is($user);
    }

    public function proposeBook(Book $book):bool
    {
        return Auth::user()->books()->contains($book);
    }

    public function resolveRequest(User $sender, Book $requestedBook, Book $proposedBook):bool
    {
        $request = TradeRequest::find([$sender->id, Auth::id(), $proposedBook->ISBN, $requestedBook->ISBN])->with('receiver');
        return $request != null && $request->response == null && Auth::user()->is($request->receiver);
    }
}
