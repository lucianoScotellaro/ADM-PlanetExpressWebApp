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
        return view('trades.received.index', ['requests'=>$user->pendingReceivedTradeRequests]);
    }

    public function show(User $user, Book $book){
        session(['receiver'=>$user->id,'requestedBook'=>$book->ISBN]);
        $activeUser = User::find(2);
        return view('trades.show-propose',['user'=>$activeUser,'books'=>$activeUser->books]);
    }

    public function store(Book $book){
        $activeUser = User::find(2);

        TradeRequest::create([
            'receiver_id'=>session('receiver'),
            'sender_id'=>$activeUser->id,
            'requested_book_ISBN'=>session('requestedBook'),
            'proposed_book_ISBN'=>$book->ISBN
        ]);

        return redirect('/');
    }

    public function update(User $sender, Book $requestedBook, Book $proposedBook){
        $activeUser = User::find(1);
        $request = TradeRequest::find([$sender->id, $activeUser->id, $proposedBook->ISBN, $requestedBook->ISBN]);

        if(request()->is('trades/requests/accept/*')){
            $request->update([
                'response'=>true
            ]);
            session(['success'=>'Richiesta accettata correttamente']);
        }elseif('trades/requests/refuse/*'){
            $request->update([
               'response'=>false
            ]);
            session(['success'=>'Richiesta rifiutata correttamente']);
        }else{
            abort(405);
        }

        return redirect('/trades/requests/received/'.$activeUser->id);
    }
}
