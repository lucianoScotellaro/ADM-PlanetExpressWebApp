<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function showBooks(User $user): Factory|View|Application
    {
        return view('users.show-books', ['user'=>$user, 'books'=>$user->books]);
    }

    public function showBooksOnLoan(User $user): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('users.show-books', ['user'=>$user, 'books'=>$user->booksOnLoan()]);
    }

    public function booksOnLoanCreate():View|Factory|Application
    {
        return view('books.search-form');
    }

    public function addBookOnLoan(User $user, Book $book): View|Factory|Application
    {
        try
        {
            $user->books()->attach($book->ISBN, ['onLoan'=>true]);
        }
        catch (\Exception $exception)
        {
            $message = $exception->getMessage();
        }
        return view('users.show-books', ["user" => $user, "books" => $user->booksOnLoan()]);
    }

    public function removeBookOnLoan(User $user, Book $book): View|Factory|Application
    {
        $user->books()->sync($book->ISBN, ['onLoan'=>false]);
        return view('users.show-books', ["user" => $user, "books" => $user->books]);
    }
}
