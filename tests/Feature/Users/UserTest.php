<?php

use App\Models\User;
use App\Models\Book;

it("should return all the only user's books", function ()
{
    $user = userWithBooks();
    $books = Book::latest()->take(10)->get();

    expect($user->books()->pluck('ISBN') == $books->pluck('ISBN'))->toBeTrue();
});

it("should return user's books with on loan property", function ()
{
    $user = userWithBooks();

    $user->books()->each(function ($book)
    {
        expect($book->pivot->onLoan)->not->toBeNull();
    });
});

it("should return all and only user's on loan books", function ()
{
    $user = userWithBooks();

    $user->books()->each(function ($book) use ($user)
    {
        if($user->booksOnLoan()->contains($book))
        {
            expect($book->pivot->onLoan)->toBe(1);
        }
        else { expect($book->pivot->onLoan)->toBe(0); }
    });
});
