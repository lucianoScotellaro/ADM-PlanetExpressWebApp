<?php

use App\Models\TradeRequest;

it('returns trade request sender', function(){
    $senderWithBooks = userWithBooks();
    $receiverWithBooks = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN
    ]);

    expect($request->sender->id)->toBe($senderWithBooks['user']->id);
});

it('returns trade request requested book', function(){
    $senderWithBooks = userWithBooks();
    $receiverWithBooks = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN
    ]);

    expect($request->requestedBook->ISBN)->toBe($receiverWithBooks['books']->first()->ISBN);
});

it('returns trade request proposed book', function(){
    $senderWithBooks = userWithBooks();
    $receiverWithBooks = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN
    ]);

    expect($request->proposedBook->ISBN)->toBe($senderWithBooks['books']->first()->ISBN);
});
