<?php

use App\Models\Book;

it('renders book detail page', function (){
    $book = Book::factory()->create();

    login()->get('/books/'.$book->ISBN)
        ->assertStatus(200)
        ->assertViewIs('books.show');
});
