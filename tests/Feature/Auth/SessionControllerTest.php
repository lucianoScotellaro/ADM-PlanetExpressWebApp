<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

it('should render login page', function ()
{
    $response = $this->get('/login');

    $response
        ->assertStatus(200)
        ->assertViewIs('auth.login');
});

it('should take login credentials, validate them, login the user, regenerate the session token and redirect on the home page',
    function (array $loginCredentials)
{
    User::create([
            'name'=>fake()->name,
            'email'=>$loginCredentials['email'],
            'password'=>$loginCredentials['password']
        ]);

    $this->get('login');
    $previousToken = Session::get('_token');

    $response = $this->post('/login', $loginCredentials);

    $response
        ->assertStatus(302)
        ->assertRedirect('/');

    expect(Session::get('_token'))->not->toBe($previousToken);
})->with([
    [
        fn() => array('email'=>'validemail@gmail.com', 'password'=>'validPassword01!')
    ]
]);

it('should take login credentials, validate them, not login the user and throw validation exception',
    function (array $loginCredentials)
    {
        $response = $this->post('/login', $loginCredentials);

        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('errors');

        expect(array_key_exists('credentials-error', Session::get('errors')->messages()))
            ->toBeTrue();
    })->with([
    [
        fn() => array('email'=>'validemail@gmail.com', 'password'=>'validPassword01!')
    ]
]);

it('should logout and redirect the user on the home page', function (User $user)
    {
        $response = login($user)->post('/logout');

        $response
            ->assertStatus(302)
            ->assertRedirect('/');

    })->with([
        fn() => User::factory()->create()
]);


