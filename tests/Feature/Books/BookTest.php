<?php

use App\Models\Book;
use App\Models\User;

it("should return book's users", function (Book $book)
{
    User::factory(4)->create();

    $book->users()->attach([1,2,3], ['onLoan'=>true]);

    $book->users()->each( function ($user)
    {
        expect($user->getAttributes())->toBe(User::find($user->id)->getAttributes());
    });

})->with([
    fn() => Book::factory()->create()
]);
