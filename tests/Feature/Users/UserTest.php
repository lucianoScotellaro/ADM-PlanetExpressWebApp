<?php

use App\Models\User;
use App\Models\Book;
use App\Models\TradeRequest;

it("should return all the only user's books", function ()
{
    $user = userWithBooks();
    $books = Book::latest()->take(10)->get();
    expect($user->books()->pluck('ISBN') == $books->pluck('ISBN'))->toBeTrue();
});

it('returns users\'s pending received trade requests', function (){
    $senderWithBooks = userWithBooks();
    $receiverWithBooks = userWithBooks();

    $pendingRequest = TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN,
        'response'=>null
    ]);

    $resolvedRequest = TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->last()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->last()->ISBN,
        'response'=>true
    ]);

    $pendingReceivedTradeRequests = $receiverWithBooks['user']->pendingReceivedTradeRequests()->get();

    expect($pendingReceivedTradeRequests->count())->toBe(1)
        ->and($pendingReceivedTradeRequests->first()->sender->id == $pendingRequest->sender->id)
        ->and($pendingReceivedTradeRequests->first()->requestedBook->ISBN == $pendingRequest->requestedBook->ISBN)
        ->and($pendingReceivedTradeRequests->first()->proposedBook->ISBN == $pendingRequest->proposedBook->ISBN)
        ->and($pendingReceivedTradeRequests->first()->response == $pendingRequest->response)
        ->toBeTrue();
});

it("should return user's books with on loan property", function ()
{
    $user = userWithBooks();
    $user->books()->each(function ($book)
    {
        expect($book->pivot->onLoan)->not->toBeNull();
    });
});

it("should return all and only user's on loan books", function ()
{
    $user = userWithBooks();

    $user->books()->each(function ($book) use ($user)
    {
        if($user->booksOnLoan()->contains($book))
        {
            expect($book->pivot->onLoan)->toBe(1);
        }
        else { expect($book->pivot->onLoan)->toBe(0); }
    });
});
