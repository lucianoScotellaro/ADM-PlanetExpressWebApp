<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RegisteredUserController extends Controller
{
    public function create(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        return view('auth.register');
    }

    public function store(): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        $validatedData = request()->validate([
            'name' => ['required', 'string', 'regex:/^[A-Za-z]+\s*[A-Za-z]*\s*[A-Za-z]*$/'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
        ]);

        User::create($validatedData);

        return redirect('/');
    }
}
