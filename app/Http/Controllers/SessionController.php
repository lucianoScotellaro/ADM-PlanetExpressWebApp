<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        return view('auth.login');
    }

    public function store(): RedirectResponse
    {
        $validatedData = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($validatedData))
        {
            request()->session()->regenerate();
            return redirect('/');
        }
        else
        {
            throw ValidationException::withMessages([
                'credentials-error' => 'Your email or password is incorrect. Please try again.'
            ]);
        }
    }

    public function destroy(): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
