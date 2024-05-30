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
    protected $primaryKey = ['sender_id', 'receiver_id', 'proposed_book_id', 'requested_book_id'];

    public function sender(){
        return $this->belongsTo(User::class, foreignKey: 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, foreignKey: 'receiver_id');
    }

    public function requestedBook(){
        return $this->belongsTo(Book::class, foreignKey: 'requested_book_id');
    }

    public function proposedBook(){
        return $this->belongsTo(Book::class, foreignKey: 'proposed_book_id');
    }

    public static function loadTrades(User $firstUser, User $secondUser)
    {
        return TradeRequest::where('response', '=', '1', 'and')
            ->where('receiver_id', '=', $firstUser->id, 'and')
            ->where('sender_id', '=', $secondUser->id)
            ->get()
            ->union(
                TradeRequest::where('response', '=', '1', 'and')
                    ->where('receiver_id', '=', $secondUser->id, 'and')
                    ->where('sender_id', '=', $firstUser->id)
                    ->get()
            );
    }
}
