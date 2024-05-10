<?php

use App\Models\TradeRequest;
use App\Models\User;

it('renders user\'s pending received trade requests', function () {
    $user = User::factory()->create();
    login($user)->get('trades/requests/received/'.$user->id)
        ->assertStatus(200)
        ->assertViewIs('trades.received.index');
});

it('doesn\'t render another user\'s pending received trade requests', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    login($user)->get('trades/requests/received/'.$anotherUser->id)
        ->assertStatus(403);
});

it('can ask for a trade request',function (){
    $receiver = userWithBooks();
    $sender = User::factory()->create();

    login($sender)->get('trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->ISBN)
        ->assertStatus(200)
        ->assertSessionHas(['receiver'=>$receiver->id,'requestedBook'=>$receiver->books()->first()->ISBN])
        ->assertViewIs('trades.show-propose');
});

it('cannot ask for a trade request to himself', function(){
    $user = userWithBooks();

    login($user)->get('trades/ask/'.$user->id.'/'.$user->books()->first()->ISBN)
        ->assertStatus(403);
});

it('can create pending trade request proposing his book', function(){
    $receiver = userWithBooks();
    $sender = userWithBooks();
    session(['receiver'=>$receiver->id, 'requestedBook'=>$receiver->books()->first()->ISBN]);

    expect($receiver->pendingReceivedTradeRequests()->count())->toBe(0);

    login($sender)->post('/trades/propose/'.$sender->books()->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/');

    expect($receiver->pendingReceivedTradeRequests()->count())->toBe(1);
});

it('cannot create pending trade request proposing not his book', function(){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    login($sender)->post('/trades/propose/'.$receiver->books()->first()->ISBN)
        ->assertStatus(403);
});

it('can accept or refuse pending trade request',function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN,
        'response'=>null
    ]);

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->ISBN.'/'.$sender->books()->first()->ISBN)
        ->assertStatus(302)
        ->assertSessionHas('success')
        ->assertRedirect('/trades/requests/received/'.$receiver->id);

    $tradeRequest = TradeRequest::find([$sender->id, $receiver->id, $sender->books()->first()->ISBN, $receiver->books()->first()->ISBN]);

    if($action == 'accept'){
        expect($tradeRequest->response)->toBe(1);
    }elseif($action == 'refuse'){
        expect($tradeRequest->response)->toBe(0);
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
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN,
        'response'=>$response
    ]);

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->ISBN.'/'.$sender->books()->first()->ISBN)
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

    login($receiver)->get('trades/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->ISBN.'/'.$sender->books()->first()->ISBN)
        ->assertStatus(403);
})->with([
    'accept',
    'refuse'
]);
