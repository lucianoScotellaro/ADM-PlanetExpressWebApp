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

    public function searchForm()
    {
        return view('users.search-form');
    }

    public function search()
    {
        $searchOn = request()->query('searchOn');
        $pageNumber = request()->query('pageNumber');

        if(!($pageNumber > 0 && in_array($searchOn, ['requestedBook', 'proposedBook']))){
            return redirect('users/search/form');
        }

        $parameters = request()->query();

        $books = Book::searchOn($parameters, $searchOn);
        return view('users.search-results', [
            'user' => auth()->user(),
            'books' => $books,
            'searchOn' => $searchOn
        ]);
    }

    public function showProposersOrClaimers(String $proposersOrClaimers, Book $book){

        if(!in_array($proposersOrClaimers, ['proposers', 'claimers'])){
            return redirect('/users/search');
        }

        return view('users.index',[
            'book' => $book,
            'users' =>  $proposersOrClaimers === 'proposers' ? $book->proposers()->get()->where('id', '!=',auth()->id()) : $book->claimers()->get()->where('id', '!=',auth()->id()),
            'proposersOrClaimers' => $proposersOrClaimers
        ]);
    }

    public function showBooks(User $user, String $state=null): Factory|View|Application
    {
        if($state === 'onloan')
        {
            $books = $user->booksOnLoan();
        }
        elseif ($state === 'ontrade')
        {
            $books = $user->booksOnTrade();
        }
        elseif ($state === 'onwishlist')
        {
            $books = $user->booksOnWishlist();
        }
        else
        {
           $books = $user->booksOnLoan()->merge($user->booksOnTrade());
        }
        return view('users.show-books', ['user'=>$user, 'books'=>$books]);
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
            if($state != 'onwishlist' && !$user->booksOnWishlist()->contains($book))
            {
                $user->books()->updateExistingPivot($book->id, [$state=>true]);
                $message = 'Book updated successfully!';
            }
            else
            {
                $message = 'This book is either on trade, on loan or already in wishlist';
            }
        }
        return redirect('/users/'.$user->id.'/books/'.$state)->with('message', $message);
    }

    public function removeBook(User $user, Book $book, String $state): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $this->validateState($state);

        if($state === 'onloan')
        {
            $bookInList = $user->booksOnLoan()->contains($book);
        }
        elseif ($state === 'ontrade')
        {
            $bookInList = $user->booksOnTrade()->contains($book);
        }
        else
        {
            $bookInList = $user->booksOnWishlist()->contains($book);
        }

        if(!$bookInList){
            abort(400);
        }

        $user->books()->updateExistingPivot($book->id, [$state=>false]);
        $this->cleanBookUser($user);

        return redirect('/users/'.$user->id.'/books/'.$state)->with('message','Book removed successfully');
    }

    public function showTransactions(User $user)
    {
        $transactions = $user->transactions();
        return view('users.show-transactions', ['trades'=>$transactions['trades'], 'loans'=>$transactions['loans']]);
    }

    public function cleanBookUser(User $user): void
    {
        $user->books()
            ->wherePivot('onLoan', '=', 0)
            ->wherePivot('onTrade', '=', 0)
            ->wherePivot('onWishlist', '=', 0)
            ->delete();
    }

    private function validateState(String $state): void
    {
        if(!in_array($state, ['onloan','ontrade','onwishlist']))
        {
            abort(404);
        }
    }
}
