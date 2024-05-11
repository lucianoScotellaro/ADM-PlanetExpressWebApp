<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    private $BASE_REQUEST_URL = 'https://www.googleapis.com/books/v1/volumes?q=';
    private $REQUEST_CONSTRAINTS = '&orderBy=newest&langRestrict=it';

    public static function searchForm(){
        return view('books.search-form');
    }

    public function search(){
//        $requestURL = $this->buildRequestURL(request()->query());
//
//        $response= json_decode(Http::get($requestURL)->body());
//
//        if($response->totalItems != 0){
//            foreach($response->items as $item){
//                if($item->volumeInfo->industryIdentifier);
//            }
//        }
        return view('books.search-results', ['user'=>User::find(1), 'books'=>Book::all()]);
    }

    private function buildRequestURL(array $parameters):string
    {
        $requestURL = $this->BASE_REQUEST_URL;

        foreach($parameters as $parameter => $value){
            switch($parameter){
                case 'title':
                    if($value != null){
                        $requestURL .= 'intitle:'.$value.'+';
                        break;
                    }
                case 'author':
                    if($value != null){
                        $requestURL .= 'inauthor:'.$value.'+';
                        break;
                    }
            }
        }
        $requestURL = rtrim($requestURL,'+');

        return $requestURL.$this->REQUEST_CONSTRAINTS;
    }
}
