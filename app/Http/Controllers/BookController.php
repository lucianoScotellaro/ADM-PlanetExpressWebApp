<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{

    public static function searchForm(){
        return view('books.search-form');
    }

    public function search(){

        /* Search book implementation */

        return view('books.search-results', ['user'=>User::find(1), 'books'=>Book::all()]);
    }
}
