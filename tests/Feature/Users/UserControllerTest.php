<?php


use App\Http\Controllers\UserController;
use App\Models\Book;
use App\Models\User;

it("should render user profile page", function (User $user){

    login($user)->get('/users/1')
        ->assertStatus(200)
        ->assertViewIs('users.show');
})->with([
    fn() => User::factory()->create()
]);

it('should render another user profile page', function(){
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    login($user)->get('users/'.$anotherUser->id)
        ->assertStatus(200)
        ->assertViewIs('users.show')
        ->assertViewHas('user', $anotherUser);
});

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

it('should render books on loan, on trade and wishlist page', function (User $user, String $state)
{
    $response = login()->get('/users/'.$user->id.'/books/'.$state);
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewIs('users.show-books')
        ->assertViewHas('user')
        ->assertViewHas('books');
})->with([
    [fn() => userWithBooks(), 'onloan'],
    [fn() => userWithBooks(), 'ontrade'],
    [fn()=> userWithBooks(), 'onwishlist']
]);

it('should display users search form', function(){
    login()->get('users/search/form')
        ->assertStatus(200)
        ->assertViewIs('users.search-form');
});

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

it('should not add a book on loan or on trade for a user if he is not the currently authenticated user', function(String $state){
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $book = Book::factory()->create();

    login($user)->post('/users/'.$anotherUser->id.'/books/'.$book->id.'/'.$state)
        ->assertStatus(403);
})->with([
    'onloan',
    'ontrade'
]);

it('should not remove a book from user\'s on loan or on trade list if the user is not the currently authenticated user', function(String $state){
    $user = User::factory()->create();
    $anotherUser = userWithBooks();

    login($user)->delete('/users/'.$anotherUser->id.'/books/'.$anotherUser->books()->first()->id.'/'.$state)
        ->assertStatus(403);
})->with([
    'onloan',
    'ontrade'
]);

it('should not remove a book from user\'s on loan or on trade list if the book is not in list', function(String $state){
    $user = User::factory()->create();
    $book = Book::factory()->create();

    if($state == 'onloan'){
        $user->books()->attach($book->id, ['onTrade'=>true]);
    }else{
        $user->books()->attach($book->id, ['onLoan'=>true]);
    }

    login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/'.$state)
        ->assertStatus(400);
})->with([
    'onloan',
    'ontrade'
]);

it('should not add book on loan or on trade for a user when \'state\' is not valid', function(String $state){
    $user = User::factory()->create();
    $book = Book::factory()->create();
    login()->post('/users/'.$user->id.'/books/'.$book->id.'/'.$state)
        ->assertStatus(403);
})->with([
    fake()->word()
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

it('should manage adding a book already in loans or trades list', function (String $state)
{
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $user->books()->attach($book->id, [$state=>true]);

    login($user)->post('/users/'.$user->id.'/books/'.$book->id.'/'.$state)
        ->assertStatus(302)
        ->assertRedirect('/users/'.$user->id.'/books/'.$state)
        ->assertSessionHas('message','Book updated successfully!');
})->with([
    'onloan',
    'ontrade'
]);

it('should manage adding a book in wishlist that is already in loans or trades or wishlist', function ()
{
    $user = userWithBooks();

    $userController = new UserController();
    $userController->cleanBookUser($user);

    $books = $user->books;
    foreach($books as $book)
    {
        login($user)->post('/users/'.$user->id.'/books/'.$book->id.'/onwishlist')
            ->assertStatus(302)
            ->assertRedirect('/users/'.$user->id.'/books/onwishlist')
            ->assertSessionHas('message','This book is either on trade, on loan or already in wishlist');
    }
});

it('should remove a book from the loans or trades or wishlist of the user', function (User $user, String $state)
{
    $book = Book::factory()->create();
    $user->books()->attach($book->id, [$state => true]);

    $response = login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/'.$state);
    expect($response)
        ->getStatusCode()->toBe(302)
        ->assertRedirect('/users/'.$user->id.'/books/'.$state);
})->with([
    [fn() => userWithBooks(), 'onloan'],
    [fn() => userWithBooks(), 'ontrade'],
    [fn() => userWithBooks(), 'onwishlist']
]);

it('should not remove a book from loans or trades or wishlist of a user if \'state\' is not valid', function (String $state){
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $user->books()->attach($book->id, ['onLoan'=>true]);
    login($user)->delete('/users/'.$user->id.'/books/'.$book->id.'/'.$state)
        ->assertStatus(404);
})->with([
    fake()->word()
]);

it('should render requestable books page', function(){
    $user = User::factory()->create();
    $anotherUser = userWithTradeableBooks();
    login($user)->get('/users/search?title=&author=&category=&searchOn=proposedBook&pageNumber=1')
        ->assertStatus(200)
        ->assertViewIs('users.search-results')
        ->assertViewHas('user', $user)
        ->assertViewHas('books');
});

it('should not render requestable books page when page number <=0', function(int $pageNumber){
    login()->get('/users/search?title=&author=&category=&searchOn=proposedBook&pageNumber='.$pageNumber)
        ->assertStatus(302)
        ->assertRedirect('users/search/form');
})->with([
    0,-2
]);

it('should not render requestable books page when \'searchOn\' is not valid', function(String $searchOn){
    login()->get('/users/search?title=&author=&category=&searchOn='.$searchOn.'&pageNumber=1')
        ->assertStatus(302)
        ->assertRedirect('users/search/form');
})->with([
    fake()->word()
]);

it('should render proposers page', function(){
    $user = User::factory()->create();
    $book = bookWithUsers();
    login($user)->get('users/search/proposers/'.$book->id)
        ->assertStatus(200)
        ->assertViewIs('users.proposers-index')
        ->assertViewHas('book',$book)
        ->assertViewHas('proposers');
});

it('should render user\'s transactions page', function (){
    $user = userWithTransactions();
    $transactions = $user->transactions();

    login($user)->get('/users/'.$user->id.'/transactions')
        ->assertStatus(200)
        ->assertViewIs('users.show-transactions')
        ->assertViewHas('trades',$transactions['trades'])
        ->assertViewHas('loans',$transactions['loans']);
});
