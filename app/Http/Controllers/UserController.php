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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function searchForm()
    {
        return view('users.search-form');
    }

    public function search()
    {
        if(!$this->validateSearchParameters()){
            return redirect('users/search/form');
        }

        $parameters = request()->query();
        $searchOn = request()->query('searchOn');

        $books = Book::searchOn($parameters, $searchOn);
        return view('users.search-results', [
            'user' => auth()->user(),
            'books' => $books
        ]);
    }

    public function showProposers(Book $book){
        return view('users.proposers-index',[
            'book' => $book,
            'proposers' =>  $book->proposers()->get()->where('id', '!=',auth()->id())
        ]);
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
        return app('App\Http\Controllers\BookController')->searchForm();
    }

    public function addBook(User $user, String $bookID, String $state): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $this->validateState($state);

        $book = Book::find($bookID);
        if($book == null){
            $book = app('App\Http\Controllers\BookController')->store($bookID);
        }

        try
        {
            $user->books()->attach($book->id, [$state=>true]);
            $message = 'Book added successfully';
        }
        catch (Exception $exception)
        {
            $user->books()->updateExistingPivot($book->id, [$state=>true]);
            $message = 'Book updated successfully!';
        }
        return redirect('/users/'.$user->id.'/books/'.$state)->with('message', $message);
    }

    public function removeBook(User $user, Book $book, String $state): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $this->validateState($state);
        $bookInList = $state == 'onloan' ? $user->booksOnLoan()->contains($book) : $user->booksOnTrade()->contains($book);

        if(!$bookInList){
            abort(400);
        }

        $user->books()->updateExistingPivot($book->id, [$state=>false]);
        $this->cleanBookUser($user);

        return redirect('/users/'.$user->id.'/books/'.$state)->with('message','Book removed successfully');
    }

    private function cleanBookUser(User $user): void
    {
        $user->books()
            ->wherePivot('onLoan', '=', 0)
            ->wherePivot('onTrade', '=', 0)
            ->delete();
    }

    private function validateState(String $state): void
    {
        if(!in_array($state, ['onloan','ontrade']))
        {
            abort(404);
        }
    }

    private function validateSearchParameters():bool
    {
        $currentPageNumber = request()->query('pageNumber');
        if($currentPageNumber < 1){
            return false;
        }

        $searchOn = request()->query('searchOn');
        if(!in_array($searchOn, ['proposedBook','requestedBook'])){
            return false;
        }

        return true;
    }
}
