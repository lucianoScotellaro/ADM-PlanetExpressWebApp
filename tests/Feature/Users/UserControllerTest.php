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

it('should render books on loan and on trade page', function (User $user, String $state)
{
    $response = login()->get('/users/'.$user->id.'/books/'.$state);
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewIs('users.show-books')
        ->assertViewHas('user')
        ->assertViewHas('books');
})->with([
    [fn() => userWithBooks(), 'onloan'],
    [fn() => userWithBooks(), 'ontrade']
]);

it('should render books search form', function ()
{
    $response = login()->get('/users/user/books/create');
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewIs('books.search-form');
});

it('should add a book on loan or on trade for a user', function (User $user, Book $book, String $state)
{
    $response = login($user)->post('/users/'.$user->id.'/books/'.$book->id.'/'.$state);
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/'.$state);
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create(), 'onloan'],
    [fn() => userWithBooks(), fn() => Book::factory()->create(), 'ontrade']
]);

it('should not fail adding a book already in loans or trades list', function (User $user, Book $book, String $state)
{
    $user->books()->attach($book->id, [$state=>true]);

    $response = login($user)->post('/users/'.$user->id.'/books/'.$book->id.'/'.$state);
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/'.$state);
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create(), 'onloan'],
    [fn() => userWithBooks(), fn() => Book::factory()->create(), 'ontrade']
]);

it("should render user's books page for invalid URL while adding a book", function (User $user, Book $book)
{
    $response = login($user)->post('/users/'.$user->id.'/books/'.$book->id.'/random_string');
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books')
        ->assertSessionHas('message');
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
]);

it('should be blocked from adding a book already in loans list', function (User $user, Book $book)
{
    $user->books()->attach($book->id, ['onLoan'=>true]);

    $response = login($user)->post('/users/'.$user->id.'/books/'.$book->id.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(403);
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
])->skip();


it('should remove a book from the loans or trades list of the user', function (User $user, String $state)
{
    $book = Book::factory()->create();
    $user->books()->attach($book->id, [$state => true]);

    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/'.$state);
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/'.$state);
})->with([
    [fn() => userWithBooks(), 'onloan'],
    [fn() => userWithBooks(), 'ontrade']
]);

it("should render user's books page for invalid URL while removing a book", function (User $user, Book $book)
{
    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/random_string');
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books')
        ->assertSessionHas('message');
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
]);

it('should be blocked from removing a book not in loans list', function (User $user, Book $book)
{
    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/onloan');
    expect($response)
        ->getStatusCode()->toBe(403);
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create()]
])->skip();

it("'should render correct message removing a book not in user's books list", function (User $user, Book $book, String $state)
{
    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/'.$state);
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/'.$state)
        ->assertSessionHas('message', 'Book not in your list');
})->with([
    [fn() => userWithBooks(), fn() => Book::factory()->create(), 'onloan'],
    [fn() => userWithBooks(), fn() => Book::factory()->create(), 'ontrade']
]);
