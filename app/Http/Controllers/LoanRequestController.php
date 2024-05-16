<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\User;

class LoanRequestController extends Controller
{
    public function index(){
        $user = auth()->user();
        return view('loans.received.index', ['user'=>$user, 'requests'=>$user->pendingReceivedLoanRequests]);
    }

    public function store(User $receiver, Book $requestedBook){
        $redirectURL = '/users/'.$receiver->id.'/books/onloan';

        $inLoans = $receiver->booksOnLoan()->contains($requestedBook);
        if(!$inLoans){
            return redirect($redirectURL)->with('notInListError', 'Selected book is not available for loan.');
        }

        $validated = request()->validate([
            'expiration'=>['required','int','min:14', 'max:60']
        ]);

        try{
            LoanRequest::create([
                'sender_id'=>auth()->id(),
                'receiver_id'=>$receiver->id,
                'requested_book_id'=>$requestedBook->id,
                'expiration'=>$validated['expiration']
            ]);
            return redirect($redirectURL)->with('success','Request sent successfully!');
        }catch (\Exception $e){
            return redirect($redirectURL)->with('alreadyExistsError','You have already sent this user this loan request');
        }
    }

    public function update(User $sender, Book $requestedBook){
        $redirectURL = '/loans/requests/received';

        $receiver = auth()->user();
        $request = LoanRequest::find([$receiver->id, $sender->id, $requestedBook->id]);

        if(request()->is('loans/requests/accept/*')){
            $request->update([
                'response'=>true
            ]);
            return redirect($redirectURL)->with('success','Request accepted successfully!');
        }elseif('loans/requests/refuse/*'){
            $request->update([
               'response'=>false
            ]);
            return redirect($redirectURL)->with('success','Request refused successfully!');
        }
    }
}
