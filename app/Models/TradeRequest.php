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
    protected $guarded = [];
    protected $primaryKey = ['sender_id', 'receiver_id', 'proposed_book_ISBN', 'requested_book_ISBN'];

    public function sender(){
        return $this->belongsTo(User::class, foreignKey: 'sender_id');
    }

    public function requestedBook(){
        return $this->belongsTo(Book::class, foreignKey: 'requested_book_ISBN');
    }

    public function proposedBook(){
        return $this->belongsTo(Book::class, foreignKey: 'proposed_book_ISBN');
    }
}