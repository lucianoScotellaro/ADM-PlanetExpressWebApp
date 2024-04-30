<?php

it('should return book\'s users', function(){
    $bookWithUsers = bookWithUsers();
    expect($bookWithUsers['book']->users()->pluck('id') == $bookWithUsers['users']->pluck('id'))
        ->toBeTrue();
});
