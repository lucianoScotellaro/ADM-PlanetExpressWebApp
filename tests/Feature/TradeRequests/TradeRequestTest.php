<?php

use App\Models\TradeRequest;

it('returns trade request sender', function(){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN
    ]);

    expect($request->sender->id)->toBe($sender->id);
});

it('returns trade request requested book', function(){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN
    ]);

    expect($request->requestedBook->ISBN)->toBe($receiver->books()->first()->ISBN);
});

it('returns trade request proposed book', function(){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN
    ]);

    expect($request->proposedBook->ISBN)->toBe($sender->books()->first()->ISBN);
});
