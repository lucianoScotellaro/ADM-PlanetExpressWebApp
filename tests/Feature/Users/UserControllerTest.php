<?php


use App\Models\Book;
use App\Models\User;

it("should render a page with all the user's books", function (User $user)
{
    $response = login()->get('/users/'.$user->id.'/books');
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewHas('books')
        ->assertViewHas('user')
        ->assertViewIs('users.show-books');
})->with([
    fn() => userWithBooks()
]);

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
    $response = login($user)->post('/users/'.$user->id.'/books/'.$book->ISBN.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/onloan');
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
]);

it('should be blocked from adding a book already in loans list', function (User $user, Book $book)
{
    $user->books()->attach($book->ISBN, ['onLoan'=>true]);

    $response = login($user)->post('/users/'.$user->id.'/books/'.$book->ISBN.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(403);
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
]);


it('should remove a book from the loans list of the user', function (User $user)
{
    $book = Book::factory()->create();
    $user->books()->attach($book->ISBN, ['onLoan' => true]);

    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->ISBN.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/onloan');
})->with([
    fn() => userWithBooks()
]);

it('should be blocked from removing a book not in loans list', function (User $user, Book $book)
{
    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->ISBN.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(403);
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
]);
