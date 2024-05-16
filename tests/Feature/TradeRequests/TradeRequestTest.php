<?php

use App\Models\TradeRequest;

it('returns trade request sender or receiver', function(string $role){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id
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
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id
    ]);

    if($role == 'requested'){
        expect($request->requestedBook->id)->toBe($receiver->books()->first()->id);
    }elseif($role == 'proposed'){
        expect($request->proposedBook->id)->toBe($sender->books()->first()->id);
    }
})->with([
    'requested',
    'proposed'
]);
