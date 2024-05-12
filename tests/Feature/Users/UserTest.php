<?php

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\TradeRequest;

//Book relationship tests
it("should return all the only user's books", function ()
{
    $user = userWithBooks();
    $books = Book::latest()->take(10)->get();
    expect($user->books()->pluck('ISBN') == $books->pluck('ISBN'))->toBeTrue();
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

//Trade Request relationship tests
it('returns users\'s pending received trade requests', function (){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $pendingRequest = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN,
        'response'=>null
    ]);

    $resolvedRequest = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->get()->last()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->get()->last()->ISBN,
        'response'=>true
    ]);

    $pendingReceivedTradeRequests = $receiver->pendingReceivedTradeRequests()->get();

    expect($pendingReceivedTradeRequests->count())->toBe(1)
        ->and($pendingReceivedTradeRequests->first()->sender->id == $pendingRequest->sender->id)
        ->and($pendingReceivedTradeRequests->first()->requestedBook->ISBN == $pendingRequest->requestedBook->ISBN)
        ->and($pendingReceivedTradeRequests->first()->proposedBook->ISBN == $pendingRequest->proposedBook->ISBN)
        ->and($pendingReceivedTradeRequests->first()->response == $pendingRequest->response)
        ->toBeTrue();
});

//Loan Request relationship tests
it('returns user\'s pending received loan requests', function (){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $pendingRequest = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'response'=>null
    ]);

    $resolvedRequest = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->get()->last()->ISBN,
        'response'=>true
    ]);

    $pendingReceivedLoanRequests = $receiver->pendingReceivedLoanRequests()->get();

    expect($pendingReceivedLoanRequests->count())->toBe(1)
        ->and($pendingReceivedLoanRequests->first()->sender->id == $pendingRequest->sender->id)
        ->and($pendingReceivedLoanRequests->first()->requestedBook->ISBN == $pendingRequest->requestedBook->ISBN)
        ->and($pendingReceivedLoanRequests->first()->response == $pendingRequest->response)
        ->toBeTrue();
});
