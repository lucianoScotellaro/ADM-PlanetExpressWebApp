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
    $receiverWithBooks = userWithBooks();

    login()->get('trades/ask/'.$receiverWithBooks['user']->id.'/'.$receiverWithBooks['books']->first()->ISBN)
        ->assertStatus(200)
        ->assertSessionHas(['receiver'=>$receiverWithBooks['user']->id,'requestedBook'=>$receiverWithBooks['books']->first()->ISBN])
        ->assertViewIs('trades.show-propose');
});

it('can create pending trade request', function(){
    $receiverWithBooks = userWithBooks();
    $senderWithBooks = userWithBooks();
    session(['receiver'=>$receiverWithBooks['user']->id, 'requestedBook'=>$receiverWithBooks['books']->first()->ISBN]);

    expect($receiverWithBooks['user']->pendingReceivedTradeRequests()->count())->toBe(0);

    login()->post('/trades/propose/'.$senderWithBooks['books']->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/');

    expect($receiverWithBooks['user']->pendingReceivedTradeRequests()->count())->toBe(1);
});

it('can accept pending trade request',function(){
    $receiverWithBooks = userWithBooks();
    $senderWithBooks = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN,
        'response'=>null
    ]);

    login($receiverWithBooks['user'])->get('trades/requests/accept/'.$senderWithBooks['user']->id.'/'.$receiverWithBooks['books']->first()->ISBN.'/'.$senderWithBooks['books']->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received/'.$receiverWithBooks['user']->id);

    $tradeRequest = TradeRequest::find([$senderWithBooks['user']->id, $receiverWithBooks['user']->id, $senderWithBooks['books']->first()->ISBN, $receiverWithBooks['books']->first()->ISBN]);
    expect($tradeRequest->response)->toBe(1);
});

it('can refuse pending trade request', function(){
    $receiverWithBooks = userWithBooks();
    $senderWithBooks = userWithBooks();

    TradeRequest::create([
        'sender_id'=>$senderWithBooks['user']->id,
        'receiver_id'=>$receiverWithBooks['user']->id,
        'requested_book_ISBN'=>$receiverWithBooks['books']->first()->ISBN,
        'proposed_book_ISBN'=>$senderWithBooks['books']->first()->ISBN,
        'response'=>null
    ]);

    login($receiverWithBooks['user'])->get('trades/requests/refuse/'.$senderWithBooks['user']->id.'/'.$receiverWithBooks['books']->first()->ISBN.'/'.$senderWithBooks['books']->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/trades/requests/received/'.$receiverWithBooks['user']->id);

    $tradeRequest = TradeRequest::find([$senderWithBooks['user']->id, $receiverWithBooks['user']->id, $senderWithBooks['books']->first()->ISBN, $receiverWithBooks['books']->first()->ISBN]);
    expect($tradeRequest->response)->toBe(0);
});
