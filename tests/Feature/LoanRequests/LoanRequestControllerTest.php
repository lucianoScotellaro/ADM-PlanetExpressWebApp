<?php

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\User;

it('can send a loan request', function(){
    $user = User::factory()->create();
    $anotherUser = userWithBooks();
    expect($anotherUser->pendingReceivedLoanRequests()->count())->toBe(0);

    login($user)->post('/loans/ask/'.$anotherUser->id.'/'.$anotherUser->books()->first()->id, ['expiration'=>20])
        ->assertStatus(302)
        ->assertRedirect('/')
        ->assertSessionHas('success');

    expect($anotherUser->pendingReceivedLoanRequests()->count())->toBe(1)
        ->and($anotherUser->pendingReceivedLoanRequests()->first()->sender->id)->toBe($user->id)
        ->and($anotherUser->pendingReceivedLoanRequests()->first()->requestedBook->id)->toBe($anotherUser->books()->first()->id)
        ->and($anotherUser->pendingReceivedLoanRequests()->first()->expiration)->toBe(20);
});

it('cannot send the same loan request to the same user multiple times', function(){
    $user = User::factory()->create();
    $anotherUser = userWithBooks();
    LoanRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->first()->id,
        'expiration'=>20
    ]);

    login($user)->post('/loans/ask/'.$anotherUser->id.'/'.$anotherUser->books()->first()->id, ['expiration'=>20])
        ->assertStatus(302)
        ->assertSessionHas('alreadyExistsError');
});

it('cannot send a loan request with expiration that is less than 14 or greater than 60', function(int $expiration){
    $user = User::factory()->create();
    $anotherUser = userWithBooks();

    login($user)->post('/loans/ask/'.$anotherUser->id.'/'.$anotherUser->books()->first()->id, ['expiration'=>$expiration])
        ->assertStatus(302)
        ->assertSessionHasErrors(['expiration']);
})->with([
    0,
    100
]);

it('cannot send a loan request to himself', function(){
    $user = userWithBooks();

    login($user)->post('loans/ask/'.$user->id.'/'.$user->books()->first()->id, ['expiration'=>20])
        ->assertStatus(403);
});

it('cannot send a loan request with a requested book that is not in receiver books list', function(){
    $user = User::factory()->create();
    $anotherUser = userWithBooks();
    $book = Book::factory()->create();

    login($user)->post('loans/ask/'.$anotherUser->id.'/'.$book->id, ['expiration'=>20])
        ->assertStatus(403);
});

it('renders user\'s pending received loan requests page', function (){
    $user = User::factory()->create();
    login($user)->get('loans/requests/received')
        ->assertStatus(200)
        ->assertViewIs('loans.received.index')
        ->assertViewHas('user',$user);
});

it('can accept or refuse a pending loan request', function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    $pendingRequest = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'response'=>null
    ]);

    login($receiver)->get('loans/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(302)
        ->assertSessionHas('success')
        ->assertRedirect('/loans/requests/received');

    $request = LoanRequest::find([$receiver->id, $sender->id, $receiver->books()->first()->id]);

    if($action == 'accept'){
        expect($request->response)->toBe(1);
    }elseif($action == 'refuse'){
        expect($request->response)->toBe(0);
    }
})->with([
    'accept',
    'refuse'
]);

it('cannot accept or refuse a resolved loan request', function(string $action, bool $response){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$receiver->books()->first()->id,
        'response'=>$response
    ]);

    login($receiver)->get('loans/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(403);
})->with([
    'accept',
    'refuse'
])->with([
    true,
    false
]);

it('cannot accept or refuse a non-existent loan request', function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    login($receiver)->get('loans/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(403);
})->with([
    'accept',
    'refuse'
]);
