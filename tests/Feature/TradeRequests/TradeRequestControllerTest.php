<?php

use App\Models\User;

it('renders user\'s received trade requests', function () {
    $user = User::factory()->create();
    login($user)->get('trades/requests/'.$user->id)
        ->assertStatus(200)
        ->assertViewIs('trades.received.index');
});
