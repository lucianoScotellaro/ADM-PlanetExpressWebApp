<?php

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;

it('should render user\'s pending received trade requests', function () {
    $user = User::factory()->create();
    login($user)->get('trades/requests/received')
        ->assertStatus(200)
        ->assertViewIs('trades.received.index');
});

it('should ask for a trade request',function (){
    $receiver = userWithTradeableBooks();
    $sender = User::factory()->create();

    login($sender)->get('trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(200)
        ->assertSessionHas(['receiver'=>$receiver->id,'requestedBook'=>$receiver->books()->first()->id])
        ->assertViewIs('trades.show-propose');
});

it('should not ask for a trade request to himself', function(){
    $user = userWithBooks();

    login($user)->get('trades/ask/'.$user->id.'/'.$user->books()->first()->id)
        ->assertStatus(403);
});

it('should not ask for a trade request where requested book is not in receiver books list', function(){
    $receiver = userWithBooks();
    $sender = User::factory()->create();
    $book = Book::factory()->create();

    login($sender)->get('trades/ask/'.$receiver->id.'/'.$book->id)
        ->assertStatus(403);
});

it('should create pending trade request proposing his book', function(){
    $receiver = userWithTradeableBooks();
    $sender = userWithBooks();
    session(['receiver'=>$receiver->id, 'requestedBook'=>$receiver->books()->first()->id]);

    expect($receiver->pendingReceivedTradeRequests()->count())->toBe(0);

    login($sender)->post('/trades/propose/'.$sender->books()->first()->id)
        ->assertStatus(302)
        ->assertSessionHas('success')
        ->assertRedirect('/users/'.$receiver->id.'/books/ontrade');

    expect($receiver->pendingReceivedTradeRequests()->count())->toBe(1);
});

it('should not create the same pending trade request for the same user', function(){
    $receiver = userWithTradeableBooks();
    $sender = userWithBooks();
    session(['receiver'=>$receiver->id, 'requestedBook'=>$receiver->books()->first()->id]);
    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id
    ]);

    login($sender)->post('/trades/propose/'.$sender->books()->first()->id)
        ->assertStatus(302)
        ->assertSessionHas('alreadyExistsError')
        ->assertRedirect('/users/'.$receiver->id.'/books/ontrade');
});

it('should not create pending trade request proposing not his book', function(){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    login($sender)->post('/trades/propose/'.$receiver->books()->first()->id)
        ->assertStatus(403);
});

it('should not create a trade request if requested book is not in receiver trades list', function(){
    $receiver = userWithLoanableBooks();

    login()->get('/trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/users/'.$receiver->id.'/books/ontrade')
        ->assertSessionHas('notInListError');
});

it('should accept or refuse pending trade request',function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id,
        'response'=>null
    ]);

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id.'/'.$sender->books()->first()->id)
        ->assertStatus(302)
        ->assertSessionHas('success')
        ->assertRedirect('/trades/requests/received');

    $request = TradeRequest::find([$sender->id, $receiver->id, $sender->books()->first()->id, $receiver->books()->first()->id]);

    if($action == 'accept'){
        expect($request->response)->toBe(1);
    }elseif($action == 'refuse'){
        expect($request->response)->toBe(0);
    }

})->with([
    'accept',
    'refuse'
]);

it('cannot accept or refuse resolved request', function(string $action, bool $response){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id,
        'response'=>$response
    ]);

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id.'/'.$sender->books()->first()->id)
        ->assertStatus(403);
})->with([
    'accept',
    'refuse'
])->with([
    true,
    false
]);

it('cannot accept or refuse non-existent trade request', function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id.'/'.$sender->books()->first()->id)
        ->assertStatus(403);
})->with([
    'accept',
    'refuse'
]);
