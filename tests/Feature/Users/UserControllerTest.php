<?php

it('renders user\'s books page', function () {
    $userWithBooks = userWithBooks();

    login()->get('/users/'.$userWithBooks['user']->id.'/books')
        ->assertStatus(200)
        ->assertViewIs('users.show-books');
});
