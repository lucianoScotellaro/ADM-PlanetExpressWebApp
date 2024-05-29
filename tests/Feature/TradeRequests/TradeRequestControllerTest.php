<?php

use App\Models\TradeRequest;
use App\Models\User;

it('should render user\'s pending received and sent trade requests', function (String $type) {
    $user = userWithBooks();
    $anotherUser = userWithBooks();
    $userBook = $user->books()->first()->id;
    $anotherUserBook = $user->books()->first()->id;

    TradeRequest::create([
        'receiver_id' => $type == 'received' ? $user->id : $anotherUser->id,
        'sender_id' => $type == 'received' ? $anotherUser->id : $user->id,
        'requested_book_id' => $type == 'received' ? $userBook : $anotherUserBook,
        'proposed_book_id' => $type == 'received' ? $anotherUserBook : $userBook
    ]);

    login($user)->get('/trades/requests/'.$type)
        ->assertStatus(200)
        ->assertViewIs('trades.'.$type)
        ->assertViewHas('requests', $type == 'received' ? $user->pendingReceivedTradeRequests : $user->pendingSentTradeRequests);
})->with([
    'received',
    'sent'
]);

it('should redirect on home page if requests type is not received or sent', function(String $type) {
    login()->get('/trades/requests/'.$type)
        ->assertStatus(302)
        ->assertRedirect('/');
})->with(
    [fake()->word()]
);

it('should ask for a trade request',function (){
    $receiver = userWithTradeableBooks();
    $sender = User::factory()->create();

    login($sender)->get('trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(200)
        ->assertSessionHas(['receiver'=>$receiver->id,'requestedBook'=>$receiver->books()->first()->id])
        ->assertViewIs('trades.show-propose');
});

it('should not ask for a trade request to himself', function(){
    $user = userWithTradeableBooks();

    login($user)->get('trades/ask/'.$user->id.'/'.$user->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/users/'.$user->id.'/books/ontrade')
        ->assertSessionHas('invalidRequest');
});

it('should not ask for a trade request where requested book is not in receiver books list', function(){
    $receiver = userWithLoanableBooks();
    $sender = User::factory()->create();

    login($sender)->get('/trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/users/'.$receiver->id.'/books/ontrade')
        ->assertSessionHas('notInListError');
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
    session(['receiver'=>$receiver->id, 'requestedBook'=>$receiver->books()->first()->id]);

    login($sender)->post('/trades/propose/'.$receiver->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/users/'.$receiver->id.'/books/ontrade')
        ->assertSessionHas('notInListError');
});

it('should not create a trade request if requested book is not in receiver trades list', function(){
    $receiver = userWithLoanableBooks();

    login()->get('/trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/users/'.$receiver->id.'/books/ontrade')
        ->assertSessionHas('notInListError');
});

it('should accept or refuse pending trade request',function(string $action){
    $receiver = userWithTradeableBooks();
    $sender = userWithTradeableBooks();
    $requestedBook = $receiver->books()->first();
    $proposedBook = $sender->books()->first();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$requestedBook->id,
        'proposed_book_id'=>$proposedBook->id,
        'response'=>null
    ]);

    login($receiver)->get('/trades/requests/'.$action.'/'.$sender->id.'/'.$requestedBook->id.'/'.$proposedBook->id)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received')
        ->assertSessionHas('success');

    $request = TradeRequest::find([$sender->id, $receiver->id, $proposedBook->id, $requestedBook->id]);

    if($action == 'accept'){
        expect($request->response)->toBe(1);
    }elseif($action == 'refuse'){
        expect($request->response)->toBe(0);
    }

})->with([
    'accept',
    'refuse'
]);

it('should remove requested and proposed books from users books list when request is accepted',function(){
    $receiver = userWithTradeableBooks();
    $sender = userWithTradeableBooks();
    $requestedBook = $receiver->books()->first();
    $proposedBook = $sender->books()->first();

    TradeRequest::create([
        'receiver_id'=>$receiver->id,
        'sender_id'=>$sender->id,
        'requested_book_id'=>$requestedBook->id,
        'proposed_book_id'=>$proposedBook->id
    ]);

    login($receiver)->get('/trades/requests/accept/'.$sender->id.'/'.$requestedBook->id.'/'.$sender->books()->first()->id);

    expect($receiver->booksOnTrade()->count())->toBe(9)
        ->and($receiver->booksOnTrade()->contains($requestedBook->id))->not->toBeTrue()
        ->and($sender->booksOnTrade()->count())->toBe(9)
        ->and($sender->booksOnTrade()->contains($proposedBook->id))->not->toBeTrue();
});

it('should not accept or refuse resolved request', function(string $action, bool $response){
    $receiver = userWithTradeableBooks();
    $sender = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'proposed_book_id'=>$sender->books()->first()->id,
        'response'=>$response
    ]);

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id.'/'.$sender->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received')
        ->assertSessionHas('alreadyResolvedError');
})->with([
    'accept',
    'refuse'
])->with([
    true,
    false
]);

it('should not accept or refuse non-existent trade request', function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id.'/'.$sender->books()->first()->id)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received')
        ->assertSessionHas('notExistsError');
})->with([
    'accept',
    'refuse'
]);
