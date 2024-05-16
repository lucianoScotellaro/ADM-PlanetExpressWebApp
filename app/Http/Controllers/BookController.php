<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Traits\FetchBooks;

class BookController extends Controller
{
    use FetchBooks;

    public function searchForm(){
        return view('books.search-form');
    }

    public function search(){
        if(!$this->validateSearchParameters()){
            return redirect('/users/user/books/create');
        }

        return view('books.search-results', [
            'user'=>User::find(1),
            'books'=>$this->fetchBooks(),
            'currentPageNumber'=>request()->query('pageNumber')
        ]);
    }

    public function store(String $bookID):Book
    {
        $bookData = $this->fetchBookData($bookID);
        return Book::create($bookData);
    }

    private function validateSearchParameters():bool
    {
        $parameters = request()->query();
        if($parameters['title'] == null && $parameters['author'] == null && $parameters['category'] == null){
            session(['noParametersError'=>'At least one parameter is required.']);
            return false;
        }

        $currentPageNumber = request()->query('pageNumber');
        if($currentPageNumber < 1){
            return false;
        }

        return true;
    }
}
