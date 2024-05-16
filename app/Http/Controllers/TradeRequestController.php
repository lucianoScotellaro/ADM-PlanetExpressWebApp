<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;
use Illuminate\Http\Request;

class TradeRequestController extends Controller
{
    public function index()
    {
        return view('trades.received.index', ['requests'=>auth()->user()->pendingReceivedTradeRequests]);
    }

    public function show(User $receiver, Book $requestedBook){
        session(['receiver'=>$receiver->id,'requestedBook'=>$requestedBook->id]);
        $user = auth()->user();
        return view('trades.show-propose',['user'=>$user,'books'=>$user->books]);
    }

    public function store(Book $proposedBook){
        $user = auth()->user();

        try {
            TradeRequest::create([
                'receiver_id'=>session('receiver'),
                'sender_id'=>$user->id,
                'requested_book_id'=>session('requestedBook'),
                'proposed_book_id'=>$proposedBook->id
            ]);
            session(['success'=>'Request sent successfully!']);
        }catch (\Exception $e){
            session(['alreadyExistsError'=>'You have already sent this user this trade request.']);
        }
        session()->forget(['receiver','requestedBook']);

        return redirect('/');
    }

    public function update(User $sender, Book $requestedBook, Book $proposedBook){
        $user = auth()->user();
        $request = TradeRequest::find([$sender->id, $user->id, $proposedBook->id, $requestedBook->id]);

        if(request()->is('trades/requests/accept/*')){
            $request->update([
                'response'=>true
            ]);
            session(['success'=>'Request accepted successfully!']);
        }elseif('trades/requests/refuse/*'){
            $request->update([
               'response'=>false
            ]);
            session(['success'=>'Request refused successfully!']);
        }

        return redirect('/trades/requests/received');
    }
}
