<?php

use App\Models\Book;
use App\Models\User;

it('should render books search form page', function(){

    login()->get('users/user/books/create')
        ->assertStatus(200)
        ->assertViewIs('books.search-form');
});

it('should render search books results page if query string has parameters', function (String $query)
{
    $user = User::factory()->create();
    login($user)->get('/books/search'.$query)
        ->assertStatus(200)
        ->assertViewIs('books.search-results')
        ->assertViewHasAll(['books','user'=>$user,'currentPageNumber'=>1]);
})->with([
    ['title, author and category' => '?title=Divina&author=Dante&category=Classic&pageNumber=1'],
    ['title and author' => '?title=Divina&author=Dante&category=&pageNumber=1'],
    ['title and category' => '?title=Divina&author=&category=Classic&pageNumber=1'],
    ['author and category' => '?title=&author=Dante&category=Classic&pageNumber=1'],
    ['title only' => '?title=Divina&author=&category=&pageNumber=1'],
    ['author only' => '?title=&author=Dante&category=&pageNumber=1'],
    ['category only' => '?title=&author=&category=Classic&pageNumber=1']
]);

it('should not render search books results page if query string has no parameters', function (){
    login()->get('/books/search?title=&author=&category=&pageNumber=1')
        ->assertStatus(302)
        ->assertRedirect('/users/user/books/create')
        ->assertSessionHas('noParametersError');
});

it('should not render search books results page if page number <= 0', function(int $pageNumber)
{
    login()->get('/books/search?title=Divina&author=Dante&category=Classic&pageNumber='.$pageNumber)
        ->assertStatus(302)
        ->assertRedirect('/users/user/books/create');
})->with([0, -2]);

it('should store selected book while adding to collection if it is not already in DB', function(String $bookID, String $state)
{
    $user = User::factory()->create();
    login($user)->post('users/1/books/'.$bookID.'/'.$state)
        ->assertStatus(302)
        ->assertRedirect('/users/1/books/'.$state)
        ->assertSessionHas('message');

    $books = Book::all();
    expect($books)->toHaveCount(1);
})->with([
    'xFr92V2k3PIC'
])->with([
    'onloan',
    'ontrade'
]);

it('should not store a book that is already in DB', function(String $bookID, String $state)
{
    $user = User::factory()->create();
    app('App\Http\Controllers\BookController')->store($bookID);
    login($user)->post('users/1/books/'.$bookID.'/'.$state)
        ->assertStatus(302)
        ->assertRedirect('/users/1/books/'.$state)
        ->assertSessionHas('message');

    $books = Book::all();
    expect($books)->toHaveCount(1);
})->with([
    'xFr92V2k3PIC'
])->with([
    'onloan',
    'ontrade'
]);

it('should remove \'noParametersError\' from session when a search with parameters is performed',function(String $queryString){
    session(['noParametersError' => 'At least one parameter is required.']);
    login()->get('books/search'.$queryString)
        ->assertStatus(200)
        ->assertSessionMissing('noParametersError');
})->with([
    '?title=Divina&author=Dante&category=Classic&pageNumber=1',
    '?title=Divina&author=&category=Classic&pageNumber=1',
    '?title=Divina&author=Dante&category=&pageNumber=1',
    '?title=&author=Dante&category=Classic&pageNumber=1',
    '?title=Divina&author=&category=&pageNumber=1',
    '?title=&author=Dante&category=&pageNumber=1',
    '?title=&author=&category=Classic&pageNumber=1'
]);
