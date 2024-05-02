<?php

use App\Models\TradeRequest;
use App\Models\User;

it('renders user\'s pending received trade requests', function () {
    $user = User::factory()->create();
    login($user)->get('trades/requests/received/'.$user->id)
        ->assertStatus(200)
        ->assertViewIs('trades.received.index');
});

it('renders trade request \'propose\' page',function (){
    $receiver = userWithBooks();

    login()->get('trades/ask/'.$receiver->id.'/'.$receiver->books()->first()->ISBN)
        ->assertStatus(200)
        ->assertSessionHas(['receiver'=>$receiver->id,'requestedBook'=>$receiver->books()->first()->ISBN])
        ->assertViewIs('trades.show-propose');
});

it('can create pending trade request', function(){
    $receiver = userWithBooks();
    $sender = userWithBooks();
    session(['receiver'=>$receiver->id, 'requestedBook'=>$receiver->books()->first()->ISBN]);

    expect($receiver->pendingReceivedTradeRequests()->count())->toBe(0);

    login()->post('/trades/propose/'.$sender->books()->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/');

    expect($receiver->pendingReceivedTradeRequests()->count())->toBe(1);
});

it('can accept pending trade request',function(){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN,
        'response'=>null
    ]);

    login($receiver)->get('trades/requests/accept/'.$sender->id.'/'.$receiver->books()->first()->ISBN.'/'.$sender->books()->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received/'.$receiver->id);

    $tradeRequest = TradeRequest::find([$sender->id, $receiver->id, $sender->books()->first()->ISBN, $receiver->books()->first()->ISBN]);
    expect($tradeRequest->response)->toBe(1);
});

it('can refuse pending trade request', function(){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_ISBN'=>$receiver->books()->first()->ISBN,
        'proposed_book_ISBN'=>$sender->books()->first()->ISBN,
        'response'=>null
    ]);

    login($receiver)->get('trades/requests/refuse/'.$sender->id.'/'.$receiver->books()->first()->ISBN.'/'.$sender->books()->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received/'.$receiver->id);

    $tradeRequest = TradeRequest::find([$sender->id, $receiver->id, $sender->books()->first()->ISBN, $receiver->books()->first()->ISBN]);
    expect($tradeRequest->response)->toBe(0);
});
