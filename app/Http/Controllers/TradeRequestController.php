<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;
use Illuminate\Http\Request;

class TradeRequestController extends Controller
{
    public function index(User $user)
    {
        return view('trades.received.index', ['requests'=>$user->receivedTradeRequests]);
    }

    public function show(User $user, Book $book){
        session(['receiver'=>$user->id,'requestedBook'=>$book->ISBN]);
        $activeUser = User::find(2); //current user
        return view('trades.show-propose',['user'=>$activeUser,'books'=>$activeUser->books]);
    }

    public function store(Book $book){
        $user = User::find(2); //current user
        //Authorization
        TradeRequest::create([
            'receiver_id'=>session('receiver'),
            'sender_id'=>$user->id,
            'requested_book_ISBN'=>session('requestedBook'),
            'proposed_book_ISBN'=>$book->ISBN
        ]);

        return redirect('/');
    }
}
