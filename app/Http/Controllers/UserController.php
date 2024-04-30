<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

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
        return BookController::searchForm();
    }

    public function addBookOnLoan(User $user, Book $book): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        try
        {
            $user->books()->attach($book->ISBN, ['onLoan'=>true]);
            $message = 'Book added successfully';
        }
        catch (Exception $exception)
        {
            $message = 'Book already added';
        }
        return redirect('/users/'.$user->id.'/books/onloan')->with('message', $message);
    }

    public function removeBookOnLoan(User $user, Book $book): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {

            $removed = $user->books()->detach($book->ISBN);
            $message = 'Book removed successfully';

            if(!$removed){
                $message = 'Book not in loans list';
            }

        return redirect('/users/'.$user->id.'/books/onloan')->with('message', $message);
    }
}
