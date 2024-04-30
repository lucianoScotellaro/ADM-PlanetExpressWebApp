<?php

use App\Models\TradeRequest;

it('returns users\'s books', function (){
    $userWithBooks = userWithBooks();
    expect($userWithBooks['user']->books()->pluck('ISBN') == $userWithBooks['books']->pluck('ISBN'))
        ->toBeTrue();
});

it('returns users\'s received trade requests', function (){
    $senderWithBooks = userWithBooks();
    $receiverWithBooks = userWithBooks();

    $requests = TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN
    ]);

    $receivedTradeRequests = $receiverWithBooks['user']->receivedTradeRequests()->get();

    expect($receivedTradeRequests->count())->toBe(1)
        ->and($receivedTradeRequests->pluck('sender_id') == $requests->pluck('sender_id'))
        ->and($receivedTradeRequests->pluck('requested_book_ISBN') == $requests->pluck('requested_book_ISBN'))
        ->and($receivedTradeRequests->pluck('proposed_book_ISBN') == $requests->pluck('proposed_book_ISBN'))
        ->toBeTrue();
});
