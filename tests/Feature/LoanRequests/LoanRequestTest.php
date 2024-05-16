<?php

use App\Models\LoanRequest;

it('returns loan request sender or receiver', function(string $role){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id
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

it('returns loan request requested book', function(){
    $sender = userWithBooks();
    $receiver = userWithBooks();

    $request = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id
    ]);

    expect($request->requestedBook->id)->toBe($receiver->books()->first()->id);
});
