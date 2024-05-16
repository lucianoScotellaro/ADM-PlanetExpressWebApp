<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

trait FetchBooks
{
    private $BASE_REQUEST_URL = 'https://www.googleapis.com/books/v1/volumes?q=';
    private $LANGUAGE_CONSTRAINTS = '&langRestrict=it,en';
    private $ORDER_CONSTRAINT = '&orderBy=relevance';

    private function fetchBooks():array
    {
        $requestURL = $this->buildRequestURL();
        $response= json_decode(Http::get($requestURL)->body());

        $books = array();
        if(property_exists($response, 'items')){
            foreach($response->items as $item){
                $book = $this->buildBook($item);
                $books[] = $book;
            }
        }

        return $books;
    }

    private function buildRequestURL():String
    {
        $parameters = request()->query();
        $currentPageNumber = request()->query('pageNumber');
        $resultsPerPage = Config::get('constants.resultsPerPage');

        $requestURL = $this->BASE_REQUEST_URL;
        foreach($parameters as $parameter => $value) {
            $requestURL = $this->updateRequestURL($requestURL, $parameter, $value);
        }

        $requestURL = rtrim($requestURL,'+');
        $requestURL .= '&maxResults='.$resultsPerPage;
        $requestURL .= '&startIndex='.($currentPageNumber - 1)*$resultsPerPage;
        $requestURL .= $this->LANGUAGE_CONSTRAINTS.$this->ORDER_CONSTRAINT;

        return $requestURL;
    }

    private function updateRequestURL(String $requestURL, String $parameter, mixed $value):String
    {
        switch ($parameter) {
            case 'title':
                if ($value != null) {
                    $requestURL .= 'intitle:' . $value . '+';
                    break;
                }
            case 'author':
                if ($value != null) {
                    $requestURL .= 'inauthor:' . $value . '+';
                    break;
                }
            case 'category':
                if ($value != null) {
                    $requestURL .= 'subject:' . $value . '+';
                    break;
                }
            default:
                break;
        }
        return $requestURL;
    }

    private function buildBook(object $item):array
    {
        $itemInfo = $item->volumeInfo;
        $book = array(
            'id'=>$item->id,
            'title'=>$itemInfo->title
        );

        $book['authors'] = property_exists($itemInfo,'authors') ? $itemInfo->authors : null;
        $book['publishedDate'] = property_exists($itemInfo,'publishedDate') ? $itemInfo->publishedDate : 'Not available';
        $book['thumbnailUrl'] = property_exists($itemInfo,'imageLinks') && property_exists($itemInfo->imageLinks,'thumbnail') ? $itemInfo->imageLinks->thumbnail : null;

        return $book;
    }

    private function fetchBookData(String $bookID):array
    {
        $response = json_decode(Http::get('https://www.googleapis.com/books/v1/volumes/'.$bookID));
        $bookInfo = $response->volumeInfo;
        $fallbackString = 'Not Available';
        return array(
            'id' => $response->id,
            'title' => $bookInfo->title,
            'author' => property_exists($bookInfo,'authors') ? $bookInfo->authors[0] : $fallbackString,
            'description' => property_exists($bookInfo,'description') ? $bookInfo->description : $fallbackString,
            'category' => property_exists($bookInfo,'categories') ? $bookInfo->categories[0] : $fallbackString,
            'publishedDate' => property_exists($bookInfo,'publishedDate') ? $bookInfo->publishedDate : $fallbackString,
            'thumbnailUrl' => property_exists($bookInfo,'imageLinks') && property_exists($bookInfo->imageLinks,'thumbnail') ? $bookInfo->imageLinks->thumbnail : null,
            'pageCount' => property_exists($bookInfo,'pageCount') ? $bookInfo->pageCount : $fallbackString
        );
    }
}
