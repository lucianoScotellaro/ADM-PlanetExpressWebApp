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
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function showBooks(User $user, String $state=null): Factory|View|Application
    {
        if($state === 'onloan')
        {
            return view('users.show-books', ['user' => $user, 'books' => $user->booksOnLoan()]);
        }
        elseif ($state === 'ontrade')
        {
            return view('users.show-books', ['user' => $user, 'books' => $user->booksOnTrade()]);
        }
        else
        {
           return view('users.show-books', ['user' => $user, 'books' => $user->books]);
        }
    }

    public function booksCreate():View|Factory|Application
    {
        return BookController::searchForm();
    }

    public function addBook(User $user, Book $book, String $state): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        if(in_array($state, ['onloan','ontrade']))
        {
            try
            {
                $user->books()->attach($book->ISBN, [$state=>true]);
                $message = 'Book added successfully';
            }
            catch (Exception $exception)
            {
                $user->books()->updateExistingPivot($book->ISBN, [$state=>true]);
                $message = 'Book updated successfully';
            }
            return redirect('/users/'.$user->id.'/books/'.$state)->with('message', $message);
        }
        return redirect('/users/'.$user->id.'/books')->with('message', 'Invalid URL');
    }

    public function removeBook(User $user, Book $book, String $state): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        if(in_array($state, ['onloan','ontrade']))
        {
            $removed = $user->books()->updateExistingPivot($book->ISBN, [$state=>false]);
            $message = 'Book removed successfully';

            if(!$removed){
                $message = 'Book not in your list';
            }

            $this->cleanBookUser($user);

            return redirect('/users/'.$user->id.'/books/'.$state)->with('message', $message);
        }
        return redirect('/users/'.$user->id.'/books')->with('message', 'Invalid URL');
    }

    private function cleanBookUser(User $user): void
    {
        $user->books()
            ->wherePivot('onLoan', false)
            ->wherePivot('onTrade', false)
            ->delete();
    }
}
