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
        $trades = TradeRequest::loadTrades($authenticated, $reviewedUser);

        $loans = LoanRequest::loadLoans($authenticated, $reviewedUser);

        return !$trades->union($loans)->isEmpty();
    }
}
