<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class TradeRequest extends Model
{
    use HasFactory;
    use HasCompositeKey;

    protected $table = 'trade_requests';
    protected $fillable = ['sender_id', 'receiver_id', 'proposed_book_ISBN', 'requested_book_ISBN'];
    protected $primaryKey = ['sender_id', 'receiver_id', 'proposed_book_ISBN', 'requested_book_ISBN'];
}
