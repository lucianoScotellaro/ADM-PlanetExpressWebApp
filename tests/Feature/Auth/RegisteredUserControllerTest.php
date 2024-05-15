<?php

use App\Models\User;

it('should render registration form page', function ()
{
    $response = $this->get('/register');

    $response
        ->assertStatus(200)
        ->assertViewIs('auth.register');
});

it('should take valid registration data, validate them and store a new user in the DB', function (array $formData)
{
    $response = $this->post('/register', $formData);

    $response
        ->assertStatus(302)
        ->assertRedirect('/');

    expect(User::latest()->first())
        ->name->toBe($formData['name'])
        ->email->toBe($formData['email'])
        ->password->not->toBeNull();

})->with('validregistrationdata');

it('should take invalid registration data, validate them and show errors', function (array $formData)
{
    $response = $this->post('/register', $formData);

    $response
        ->assertStatus(302)
        ->assertSessionHas('errors');

    expect(User::latest()->first())
        ->name->not->toBe($formData['name'])
        ->email->not->toBe($formData['email']);

})->with('invalidregistrationdata');
