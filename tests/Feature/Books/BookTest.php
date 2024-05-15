<?php

use App\Models\Book;
use App\Models\User;

it('should return book\'s users', function(){
    $book = bookWithUsers();
    $users = User::latest()->take(5)->get();
    expect($book->users()->pluck('id') == $users->pluck('id'))
        ->toBeTrue();
});

it('should return book\'s proposers', function(){
    User::factory(5)->create();
    $book = Book::factory()->create();

    $book->users()->attach(1,['onLoan'=>true,'onTrade'=>true]);
    $book->users()->attach(2,['onLoan'=>true,'onTrade'=>false]);
    $book->users()->attach(3,['onLoan'=>false,'onTrade'=>true]);

    $proposers = $book->proposers()->get();
    expect($proposers->count())->toBe(3)
        ->and($proposers->pluck('id')->toArray())->toBe([1,2,3]);
});

it('should return books matching search parameters that have at least one proposer or claimant',function(array $parameters, String $searchOn){
    User::factory(2)->create();
    $firstBook = Book::create([
        'id'=>'ID1',
        'title'=> 'Naruto 1',
        'author'=> 'Masashi Kishimoto',
        'category'=> 'Manga'
    ]);
    $secondBook = Book::create([
        'id'=>'ID2',
        'title'=> 'Naruto 2',
        'author'=> 'Masashi Kishimoto',
        'category'=> 'Manga'
    ]);
    $firstBook->users()->attach(2,['onLoan'=>true,'onTrade'=>false]);

    $searchResults = Book::searchOn($parameters, $searchOn);
    expect($searchResults->count())->toBe(1)->and($searchResults->pluck('id')->toArray())
        ->toBe(['ID1']);

})->with([
    [['title'=>'Naruto', 'author'=>'Masashi Kishimoto', 'category'=>'Manga', 'pageNumber'=>1]],
    [['title'=>'Naruto','author'=>null, 'category'=>null, 'pageNumber'=>1]],
    [['title'=>null,'author'=>'Masashi Kishimoto', 'category'=>null, 'pageNumber'=>1]],
    [['title'=>null,'author'=>null,'category'=>'Manga', 'pageNumber'=>1]],
    [['title'=>null,'author'=>null,'category'=>null, 'pageNumber'=>1]]
])->with([
    'proposedBook'
]);

it('should paginate \'search on requested\' and \'search on proposed\' results',function(array $parameters, String $searchOn){
    User::factory(2)->create();
    $books = Book::factory(13)->create();
    foreach ($books as $book){
        $book->users()->attach(2,['onLoan'=>true,'onTrade'=>true]);
    }

    $searchResults = Book::searchOn($parameters, $searchOn);
    if($parameters['pageNumber'] == 1){
        expect($searchResults->count())->toBe(10);
    }elseif($parameters['pageNumber'] == 2){
        expect($searchResults->count())->toBe(3);
    }
})->with([
    [['title'=>null,'author'=>null,'category'=>null, 'pageNumber'=>1]],
    [['title'=>null,'author'=>null, 'category'=>null, 'pageNumber'=>2]]
])->with([
    'proposedBook'
]);
