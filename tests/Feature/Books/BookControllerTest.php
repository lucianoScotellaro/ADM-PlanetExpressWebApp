<?php

it('should render a page with all the books in the database', function ()
{
    $response = login()->get('/books/search');
    expect($response)
        ->getStatusCode()->toBe(200)
        ->assertViewHas('books')
        ->assertViewHas('user')
        ->assertViewIs('books.search-results');
});
