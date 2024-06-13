<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\TradeRequest;
use App\Models\User;

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

// Book utils functions
function bookWithUsers():Book
{
    $book = Book::factory()->create();
    $users = User::factory(5)->create();

    $users->each(function ($user) use ($book) {
        $book->users()->attach($user->id, ['onLoan'=>fake()->boolean]);
    });

    return $book;
}

//User utils functions
function userWithBooks():User
{
    $user = User::factory()->create();
    $books = Book::factory(20)->create();

    $books->each(function ($book) use ($user)
    {
        $user->books()->attach($book->id, ['onLoan'=>fake()->boolean]);
        $user->books()->updateExistingPivot($book->id, ['onTrade'=>fake()->boolean]);
    });

    $books = Book::factory(20)->create();
    $books->each(function ($book) use ($user)
    {
        $user->books()->attach($book->id, ['onWishlist'=>fake()->boolean]);
    });

    return $user;
}
function userWithTradeableBooks():User
{
    $user = User::factory()->create();
    $books = Book::factory(10)->create();

    $books->each(function ($book) use ($user)
    {
        $user->books()->attach($book->id, ['onTrade'=>true]);
    });
    return $user;
}

function userWithLoanableBooks():User
{
    $user = User::factory()->create();
    $books = Book::factory(10)->create();

    $books->each(function ($book) use ($user)
    {
        $user->books()->attach($book->id, ['onLoan'=>true]);
    });
    return $user;
}

function userWithWishlistedBooks(): User
{
    $user = User::factory()->create();
    $books = Book::factory(10)->create();

    $books->each(function ($book) use ($user)
    {
        $user->books()->attach($book->id, ['onWishlist'=>true]);
    });
    return $user;
}

function userWithTransactions(){
    $user = userWithBooks();
    $anotherUser = userWithBooks();

    //Transaction
    TradeRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->first()->id,
        'proposed_book_id'=>$user->books()->first()->id,
        'response'=>true
    ]);

    //Non-transaction (refused)
    TradeRequest::create([
        'sender_id'=>$anotherUser->id,
        'receiver_id'=>$user->id,
        'requested_book_id'=>$user->books()->get()->last()->id,
        'proposed_book_id'=>$anotherUser->books()->get()->last()->id,
        'response'=>true
    ]);

    //Transaction
    LoanRequest::create([
        'sender_id'=>$user->id,
        'receiver_id'=>$anotherUser->id,
        'requested_book_id'=>$anotherUser->books()->first()->id,
        'response'=>true
    ]);

    //Non-transaction (refused)
    LoanRequest::create([
        'sender_id'=>$anotherUser->id,
        'receiver_id'=>$user->id,
        'requested_book_id'=>$user->books()->get()->last()->id,
        'response'=>true
    ]);

    return $user;
}

function login ($user = null)
{
    return test()->actingAs($user ?? User::factory()->create());
}
