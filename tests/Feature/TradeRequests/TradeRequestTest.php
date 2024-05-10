<?php

use App\Models\TradeRequest;

it('returns trade request sender or receiver', function(string $role){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN
    ]);

    if($role == 'sender'){
        expect($request->sender->id)->toBe($sender->id);
    }elseif($role == 'receiver'){
        expect($request->receiver->id)->toBe($receiver->id);
    }
})->with([
    'sender',
    'receiver'
]);

it('returns trade request requested or proposed book', function(string $role){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN
    ]);

    if($role == 'requested'){
        expect($request->requestedBook->ISBN)->toBe($receiver->books()->first()->ISBN);
    }elseif($role == 'proposed'){
        expect($request->proposedBook->ISBN)->toBe($sender->books()->first()->ISBN);
    }
})->with([
    'requested',
    'proposed'
]);
