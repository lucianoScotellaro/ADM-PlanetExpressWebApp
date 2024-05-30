<?php

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\User;

it('should send a loan request', function(){
    $user = User::factory()->create();
    $anotherUser = userWithLoanableBooks();
    expect($anotherUser->pendingReceivedLoanRequests()->count())->toBe(0);

    login($user)->post('/loans/ask/'.$anotherUser->id.'/'.$anotherUser->books()->first()->id, ['expiration'=>20])
        ->assertStatus(302)
        ->assertRedirect('/users/'.$anotherUser->id.'/books/onloan')
        ->assertSessionHas('success');

    expect($anotherUser->pendingReceivedLoanRequests()->count())->toBe(1)
        ->and($anotherUser->pendingReceivedLoanRequests()->first()->sender->id)->toBe($user->id)
        ->and($anotherUser->pendingReceivedLoanRequests()->first()->requestedBook->id)->toBe($anotherUser->books()->first()->id)
        ->and($anotherUser->pendingReceivedLoanRequests()->first()->expiration)->toBe(20);
});

it('should not send the same loan request to the same user multiple times', function(){
    $user = User::factory()->create();
    $anotherUser = userWithLoanableBooks();
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

it('should not send a loan request with expiration that is less than 14 or greater than 60', function(int $expiration){
    $user = User::factory()->create();
    $anotherUser = userWithLoanableBooks();

    login($user)->post('/loans/ask/'.$anotherUser->id.'/'.$anotherUser->books()->first()->id, ['expiration'=>$expiration])
        ->assertStatus(302)
        ->assertSessionHasErrors(['expiration']);
})->with([
    0,
    100
]);

it('should not send a loan request to himself', function(){
    $user = userWithBooks();

    login($user)->post('loans/ask/'.$user->id.'/'.$user->books()->first()->id, ['expiration'=>20])
        ->assertStatus(403);
});

it('should not send a loan request with a requested book that is not in receiver books list', function(){
    $user = User::factory()->create();
    $anotherUser = userWithBooks();
    $book = Book::factory()->create();

    login($user)->post('loans/ask/'.$anotherUser->id.'/'.$book->id, ['expiration'=>20])
        ->assertStatus(403);
});

it('should not send a loan request for a book is not in receiver\'s loans list', function(){
    $receiver = userWithTradeableBooks();

    login()->post('/loans/ask/'.$receiver->id.'/'.$receiver->books()->first()->id, ['expiration'=>20])
        ->assertStatus(302)
        ->assertRedirect('/users/'.$receiver->id.'/books/onloan')
        ->assertSessionHas('notInListError');
});

it('should render user\'s pending received and sent loan requests', function (String $type) {
    $user = userWithBooks();
    $anotherUser = userWithBooks();
    $userBook = $user->books()->first()->id;
    $anotherUserBook = $user->books()->first()->id;

    LoanRequest::create([
        'receiver_id' => $type == 'received' ? $user->id : $anotherUser->id,
        'sender_id' => $type == 'received' ? $anotherUser->id : $user->id,
        'requested_book_id' => $type == 'received' ? $userBook : $anotherUserBook,
        'expiration'=> 14
    ]);

    login($user)->get('/loans/requests/'.$type)
        ->assertStatus(200)
        ->assertViewIs('loans.'.$type)
        ->assertViewHas('requests', $type == 'received' ? $user->pendingReceivedLoanRequests : $user->pendingSentLoanRequests);
})->with([
    'received',
    'sent'
]);

it('should redirect to home page if loan requests type is not received or sent',function(String $type){
    login()->get('/loans/requests/'.$type)
        ->assertStatus(302)
        ->assertRedirect('/');
})->with([
    fake()->word()
]);

it('should accept or refuse a pending loan request', function(string $action){
    $receiver = userWithLoanableBooks();
    $sender = userWithBooks();
    $requestedBook  = $receiver->books()->first();

    $pendingRequest = LoanRequest::create([
        'sender_id'=>$sender->id,
        'receiver_id'=>$receiver->id,
        'requested_book_id'=>$requestedBook->id,
        'response'=>null
    ]);

    login($receiver)->get('loans/requests/'.$action.'/'.$sender->id.'/'.$requestedBook->id)
        ->assertStatus(302)
        ->assertSessionHas('success')
        ->assertRedirect('/loans/requests/received');

    $request = LoanRequest::find([$receiver->id, $sender->id, $requestedBook->id]);

    if($action == 'accept'){
        expect($request->response)->toBe(1);
    }elseif($action == 'refuse'){
        expect($request->response)->toBe(0);
    }
})->with([
    'accept',
    'refuse'
]);

it('should remove requested book from receiver books list when request is accepted', function(){
    $receiver = userWithLoanableBooks();
    $sender = userWithLoanableBooks();
    $requestedBook = $receiver->books()->first();

    LoanRequest::create([
        'receiver_id'=>$receiver->id,
        'sender_id'=>$sender->id,
        'requested_book_id'=>$requestedBook->id,
        'expiration'=>20
    ]);

    login($receiver)->get('/loans/requests/accept/'.$sender->id.'/'.$requestedBook->id);

    expect($receiver->booksOnLoan()->count())->toBe(9)
        ->and($receiver->booksOnLoan()->contains($requestedBook->id))->not->toBeTrue();
});

it('should not accept or refuse a resolved loan request', function(string $action, bool $response){
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

it('should not accept or refuse a non-existent loan request', function(string $action){
    $receiver = userWithBooks();
    $sender = userWithBooks();

    login($receiver)->get('loans/requests/'.$action.'/'.$sender->id.'/'.$receiver->books()->first()->id)
        ->assertStatus(403);
})->with([
    'accept',
    'refuse'
]);
