<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;

class LoanRequest extends Model
{
    use HasFactory;
    use HasCompositeKey;

    protected $guarded = [];
    protected $primaryKey = ['receiver_id', 'sender_id', 'requested_book_id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, foreignKey: 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, foreignKey: 'receiver_id');
    }

    public function requestedBook(): BelongsTo
    {
        return $this->belongsTo(Book::class, foreignKey: 'requested_book_id');
    }

    public static function loadLoans(User $firstUser, User $secondUser)
    {
        return LoanRequest::where('response', '=', '1', 'and')
            ->where('receiver_id', '=', $firstUser->id, 'and')
            ->where('sender_id', '=', $secondUser->id)
            ->get()
            ->union(
                LoanRequest::where('response', '=', '1', 'and')
                    ->where('receiver_id', '=', $secondUser->id, 'and')
                    ->where('sender_id', '=', $firstUser->id)
                    ->get()
            );
    }
}
