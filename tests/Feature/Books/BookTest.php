<?php

use App\Models\User;

it('should return book\'s users', function(){
    $book = bookWithUsers();
    $users = User::latest()->take(5)->get();
    expect($book->users()->pluck('id') == $users->pluck('id'))
        ->toBeTrue();
});
