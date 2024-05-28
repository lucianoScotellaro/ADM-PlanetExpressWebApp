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
        $user = auth()->user();

        if($user->is($receiver)){
            return redirect('/users/'.$user->id.'/books/ontrade')->with('invalidRequest', 'Cannot ask for a book to yourself.');
        }

        $inTrades = $receiver->booksOnTrade()->contains($requestedBook);
        if(!$inTrades){
            return redirect('/users/'.$receiver->id.'/books/ontrade')->with('notInListError', 'Selected book is not available for trades.');
        }

        session(['receiver'=>$receiver->id,'requestedBook'=>$requestedBook->id]);
        return view('trades.show-propose',['user'=>$user,'books'=>$user->books]);
    }

    public function store(Book $proposedBook){
        $redirectURL = '/users/'.session('receiver').'/books/ontrade';
        $user = auth()->user();

        $inBooksList = $user->books()->get()->contains($proposedBook->id);
        if(!$inBooksList){
            return redirect($redirectURL)->with('notInListError', 'Selected book is not in your books list.');
        }

        try {
            TradeRequest::create([
                'receiver_id'=>session('receiver'),
                'sender_id'=>$user->id,
                'requested_book_id'=>session('requestedBook'),
                'proposed_book_id'=>$proposedBook->id
            ]);
            session()->forget(['receiver','requestedBook']);
            return redirect($redirectURL)->with('success','Request sent successfully!');
        }catch (\Exception $e){
            session()->forget(['receiver','requestedBook']);
            return redirect($redirectURL)->with('alreadyExistsError','You have already sent this user this trade request.');
        }
    }

    public function update(User $sender, Book $requestedBook, Book $proposedBook){
        $user = auth()->user();
        $request = TradeRequest::find([$sender->id, $user->id, $proposedBook->id, $requestedBook->id]);

        if($request === null){
            return redirect('/trades/requests/received')->with('notExistsError', 'Request does not exist.');
        }

        if($request->response !== null){
            return redirect('/trades/requests/received')->with('alreadyResolvedError', 'Request was already resolved.');
        }

        if(request()->is('trades/requests/accept/*')){
            $request->update([
                'response'=>true
            ]);
            $user->books()->detach($requestedBook->id);
            $sender->books()->detach($proposedBook->id);
            return redirect('/trades/requests/received')->with('success','Request accepted successfully!');
        }elseif('trades/requests/refuse/*'){
            $request->update([
               'response'=>false
            ]);
            return redirect('/trades/requests/received')->with('success','Request refused successfully!');
        }
    }
}
