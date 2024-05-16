<?php

use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;

it('renders user\'s pending received loan requests page', function (){
    $user = User::factory()->create();
    login($user)->get('loans/requests/received/'.$user->id)
        ->assertStatus(200)
        ->assertViewIs('loans.received.index');
});

it('doesn\'t render another user\'s pending received loan requests page', function (){
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    login($user)->get('loans/requests/received/'.$anotherUser->id)
        ->assertStatus(403);
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
        ->assertRedirect('/loans/requests/received/'.$receiver->id);

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
