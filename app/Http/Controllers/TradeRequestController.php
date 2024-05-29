<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\TradeRequest;
use App\Models\User;
use Illuminate\Http\Request;

class TradeRequestController extends Controller
{
    public static String $usersBaseURL = '/users/';
    public static String $onTradeBooks = '/books/ontrade';

    public function index(String $type)
    {
        $user = auth()->user();

        if(!in_array($type,['received','sent'])){
            return redirect('/');
        }

        $requests = $type == 'received' ? $user->pendingReceivedTradeRequests : $user->pendingSentTradeRequests;
        return view('trades.'.$type, ['requests' => $requests]);
    }

    public function show(User $receiver, Book $requestedBook){
        $user = auth()->user();

        if($user->is($receiver)){
            return redirect(self::$usersBaseURL.$user->id.self::$onTradeBooks)->with('invalidRequest', 'Cannot ask for a book to yourself.');
        }

        $inTrades = $receiver->booksOnTrade()->contains($requestedBook);
        if(!$inTrades){
            return redirect(self::$usersBaseURL.$receiver->id.self::$onTradeBooks)->with('notInListError', 'Selected book is not available for trades.');
        }

        session(['receiver'=>$receiver->id,'requestedBook'=>$requestedBook->id]);
        return view('trades.show-propose',['user'=>$user,'books'=>$user->books]);
    }

    public function store(Book $proposedBook){
        $redirectURL = self::$usersBaseURL.session('receiver').self::$onTradeBooks;
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
        $redirectURL = '/trades/requests/received';
        $user = auth()->user();
        $request = TradeRequest::find([$sender->id, $user->id, $proposedBook->id, $requestedBook->id]);

        if($request === null){
            return redirect($redirectURL)->with('notExistsError', 'Request does not exist.');
        }

        if($request->response !== null){
            return redirect($redirectURL)->with('alreadyResolvedError', 'Request was already resolved.');
        }

        if(request()->is('trades/requests/accept/*')){
            $request->update([
                'response'=>true
            ]);
            $user->books()->detach($requestedBook->id);
            $sender->books()->detach($proposedBook->id);
        }elseif('trades/requests/refuse/*'){
            $request->update([
               'response'=>false
            ]);
        }
        return redirect($redirectURL)->with('success','Request resolved successfully!');
    }
}
