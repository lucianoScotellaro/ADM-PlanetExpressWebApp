<?php


use App\Models\Book;
use App\Models\User;

it('should render books on loan page', function (User $user)
{
    $response = login()->get('/users/'.$user->id.'/books/onloan');
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewIs('users.show-books');
})->with([
    fn() => userWithBooks()
]);

it('should render books search form', function ()
{
    $response = login()->get('/users/user/books/onloan/create');
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewIs('books.search-form');
});

it('should add a book on loan for a user', function (User $user, Book $book)
{
    $response = login()->post('/users/'.$user->id.'/books/'.$book->ISBN.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/onloan');
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
]);

it('should remove a book from the loans list of the user', function (User $user)
{
    $book = Book::latest()->first();

    $response = login()->delete('/users/'.$user->id.'/books/'.$book->ISBN.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/onloan');
})->with([
    fn() => userWithBooks()
]);
