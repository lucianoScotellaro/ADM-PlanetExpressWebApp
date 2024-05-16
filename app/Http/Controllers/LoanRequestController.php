<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Http\Request;

class LoanRequestController extends Controller
{
    public function index(User $receiver){
        return view('loans.received.index', ['user'=>$receiver, 'requests'=>$receiver->pendingReceivedLoanRequests]);
    }

    public function update(User $sender, Book $requestedBook){
        $receiver = User::find(1);
        $request = LoanRequest::find([$receiver->id, $sender->id, $requestedBook->id]);

        if(request()->is('loans/requests/accept/*')){
            $request->update([
                'response'=>true
            ]);
            session(['success'=>'Richiesta accettata con successo.']);
        }elseif('loans/requests/refuse/*'){
            $request->update([
               'response'=>false
            ]);
            session(['success'=>'Richiesta rifiutata con successo.']);
        }

        return redirect('/loans/requests/received/'.$receiver->id);
    }
}
