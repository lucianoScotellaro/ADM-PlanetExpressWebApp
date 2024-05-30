<?php

namespace App\Policies;

use App\Models\LoanRequest;
use App\Models\TradeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReviewPolicy
{
    public function reviewUser(User $authenticated, User $reviewedUser):bool
    {
        $trades = TradeRequest::where('response', '=', '1', 'and')
            ->where('receiver_id', '=', $authenticated->id, 'and')
            ->where('sender_id', '=', $reviewedUser->id)
            ->get()
            ->union(
                TradeRequest::where('response', '=', '1', 'and')
                    ->where('receiver_id', '=', $reviewedUser->id, 'and')
                    ->where('sender_id', '=', $authenticated->id)
                    ->get()
            );

        $loans = LoanRequest::where('response', '=', '1', 'and')
            ->where('receiver_id', '=', $authenticated->id, 'and')
            ->where('sender_id', '=', $reviewedUser->id)
            ->get()
            ->union(
                LoanRequest::where('response', '=', '1', 'and')
                ->where('receiver_id', '=', $reviewedUser->id, 'and')
                ->where('sender_id', '=', $authenticated->id)
                ->get()
            );
        return !$trades->union($loans)->isEmpty();
    }
}
