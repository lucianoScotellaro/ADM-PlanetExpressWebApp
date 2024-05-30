<?php

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\TradeRequest;

//Book relationship tests
it("should return all and only user's books", function ()
{
    $user = userWithBooks();
    $books = Book::latest()->take(40)->orderBy('id')->get();
    expect($user->books()->pluck('id') == $books->pluck('id'))->toBeTrue();
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
it('should return users\'s pending received trade requests', function (){
    $sender = userWithBooks();
    $receiver = userWithTradeableBooks();

    $pendingRequest = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id,
        'response'=>null
    ]);

    $resolvedRequest = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->get()->last()->id,
        'proposed_book_id'=>$sender->books()->get()->last()->id,
        'response'=>true
    ]);

    $pendingReceivedTradeRequests = $receiver->pendingReceivedTradeRequests()->get();

    expect($pendingReceivedTradeRequests->count())->toBe(1)
        ->and($pendingReceivedTradeRequests->first()->sender->id == $pendingRequest->sender_id)->toBeTrue()
        ->and($pendingReceivedTradeRequests->first()->requestedBook->id == $pendingRequest->requested_book_id)->toBeTrue()
        ->and($pendingReceivedTradeRequests->first()->proposedBook->id == $pendingRequest->proposed_book_id)->toBeTrue()
        ->and($pendingReceivedTradeRequests->first()->response == $pendingRequest->response)->toBeTrue();
});

it('should return user\'s pending sent trade requests', function(){
    $user = userWithBooks();
    $anotherUser = userWithTradeableBooks();

    $pendingRequest = TradeRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->first()->id,
        'proposed_book_id'=>$user->books()->first()->id,
        'response'=>null
    ]);

    $resolvedRequest = TradeRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->get()->last()->id,
        'proposed_book_id'=>$user->books()->get()->last()->id,
        'response'=>true
    ]);

    $pendingSentTradeRequests = $user->pendingSentTradeRequests()->get();

    expect($pendingSentTradeRequests->count())->toBe(1)
        ->and($pendingSentTradeRequests->first()->receiver->id == $pendingRequest->receiver_id)->toBeTrue()
        ->and($pendingSentTradeRequests->first()->proposedBook->id == $pendingRequest->proposed_book_id)->toBeTrue()
        ->and($pendingSentTradeRequests->first()->requestedBook->id == $pendingRequest->requested_book_id)->toBeTrue()
        ->and($pendingSentTradeRequests->first()->response == $pendingRequest->response)->toBeTrue();
});

//Loan Request relationship tests
it('should return user\'s pending received loan requests', function (){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $pendingRequest = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'response'=>null
    ]);

    $resolvedRequest = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->get()->last()->id,
        'response'=>true
    ]);

    $pendingReceivedLoanRequests = $receiver->pendingReceivedLoanRequests()->get();

    expect($pendingReceivedLoanRequests->count())->toBe(1)
        ->and($pendingReceivedLoanRequests->first()->sender->id == $pendingRequest->sender_id)->toBeTrue()
        ->and($pendingReceivedLoanRequests->first()->requestedBook->id == $pendingRequest->requested_book_id)->toBeTrue()
        ->and($pendingReceivedLoanRequests->first()->response == $pendingRequest->response)->toBeTrue();
});

it('should return user\'s pending sent loan requests', function(){
    $user = userWithBooks();
    $anotherUser = userWithLoanableBooks();

    $pendingRequest = LoanRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->first()->id,
        'response'=>null
    ]);

    $resolvedRequest = LoanRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->get()->last()->id,
        'response'=>true
    ]);

    $pendingSentLoanRequests = $user->pendingSentLoanRequests()->get();

    expect($pendingSentLoanRequests->count())->toBe(1)
        ->and($pendingSentLoanRequests->first()->receiver->id == $pendingRequest->receiver_id)->toBeTrue()
        ->and($pendingSentLoanRequests->first()->requestedBook->id == $pendingRequest->requested_book_id)->toBeTrue()
        ->and($pendingSentLoanRequests->first()->response == $pendingRequest->response)->toBeTrue();
});

//Transactions test
it('should return user\'s past transactions', function(String $role){
    $user = userWithBooks();
    $anotherUser = userWithBooks();

    //Transaction
    $trade = TradeRequest::create([
        'sender_id'=>$role == 'sender' ? $user->id : $anotherUser->id,
        'receiver_id'=>$role == 'sender' ? $anotherUser->id : $user->id,
        'requested_book_id'=>$role == 'sender' ? $anotherUser->books()->first()->id : $user->books()->first()->id,
        'proposed_book_id'=>$role == 'sender' ? $user->books()->first()->id : $anotherUser->books()->first()->id,
        'response'=>true
    ]);

    //Non-transaction (refused)
    TradeRequest::create([
        'sender_id'=>$role == 'sender' ? $user->id : $anotherUser->id,
        'receiver_id'=>$role == 'sender' ? $anotherUser->id : $user->id,
        'requested_book_id'=>$role == 'sender' ? $anotherUser->books()->get()->last()->id : $user->books()->get()->last()->id,
        'proposed_book_id'=>$role == 'sender' ? $user->books()->get()->last()->id : $anotherUser->books()->get()->last()->id,
        'response'=>false
    ]);

    //Transaction
    $loan = LoanRequest::create([
        'sender_id'=>$role == 'sender' ? $user->id : $anotherUser->id,
        'receiver_id'=>$role == 'sender' ? $anotherUser->id : $user->id,
        'requested_book_id'=>$role == 'sender' ? $anotherUser->books()->first()->id : $user->books()->first()->id,
        'response'=>true
    ]);

    //Non-transaction (refused)
    LoanRequest::create([
        'sender_id'=>$role == 'sender' ? $user->id : $anotherUser->id,
        'receiver_id'=>$role == 'sender' ? $anotherUser->id : $user->id,
        'requested_book_id'=>$role == 'sender' ? $anotherUser->books()->get()->last()->id : $user->books()->get()->last()->id,
        'response'=>false
    ]);

    $transactions = $user->transactions();
    expect(count($transactions['trades']))->toBe(1)
        ->and(count($transactions['loans']))->toBe(1);

    $tradeFromTransactions = $transactions['trades']->first();
    $loanFromTransactions = $transactions['loans']->first();
    if($role == 'sender'){
        expect($tradeFromTransactions->receiver->id == $trade->receiver_id)->toBeTrue()
            ->and($tradeFromTransactions->proposedBook->id == $trade->proposed_book_id)->toBeTrue()
            ->and($tradeFromTransactions->requestedBook->id == $trade->requested_book_id)->toBeTrue()
            ->and($loanFromTransactions->receiver->id == $loan->receiver_id)->toBeTrue()
            ->and($loanFromTransactions->requestedBook->id == $loan->requested_book_id)->toBeTrue();
    }elseif($role == 'receiver'){
        expect($tradeFromTransactions->sender->id == $trade->sender_id)->toBeTrue()
            ->and($tradeFromTransactions->proposedBook->id == $trade->proposed_book_id)->toBeTrue()
            ->and($tradeFromTransactions->requestedBook->id == $trade->requested_book_id)->toBeTrue()
            ->and($loanFromTransactions->sender->id == $loan->sender_id)->toBeTrue()
            ->and($loanFromTransactions->requestedBook->id == $loan->requested_book_id)->toBeTrue();
    }
})->with([
    'sender',
    'receiver'
]);
