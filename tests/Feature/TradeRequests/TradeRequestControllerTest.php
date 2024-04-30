<?php

use App\Models\User;

it('renders user\'s received trade requests', function () {
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

it('creates trade request', function(){
    $receiverWithBooks = userWithBooks();
    $senderWithBooks = userWithBooks();
    session(['receiver'=>$receiverWithBooks['user']->id, 'requestedBook'=>$receiverWithBooks['books']->first()->ISBN]);

    login()->post('/trades/propose/'.$senderWithBooks['books']->first()->ISBN)
        ->assertStatus(302)
        ->assertRedirect('/');
});
