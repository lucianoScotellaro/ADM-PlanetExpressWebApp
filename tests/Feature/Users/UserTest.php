<?php

use App\Models\TradeRequest;

it('returns users\'s books', function (){
    $userWithBooks = userWithBooks();
    expect($userWithBooks['user']->books()->pluck('ISBN') == $userWithBooks['books']->pluck('ISBN'))
        ->toBeTrue();
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
