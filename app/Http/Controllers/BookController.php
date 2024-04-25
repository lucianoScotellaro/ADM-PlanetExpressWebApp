<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', ['books' => $books, 'user'=>Auth::user()]);
    }

    public function search(Request $request){

        /* Search book implementation */

        return view('books.search-results', ['user'=>User::find(1), 'books'=>Book::all()]);
    }

}
