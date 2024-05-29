<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Books Relationships
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)
            ->withTimestamps()
            ->withPivot('onLoan')
            ->withPivot('onTrade');
    }

    public function booksOnLoan(): Collection
    {
        return $this->books()->wherePivot('onLoan', 1)->get();
    }

    public function booksOnTrade(): Collection
    {
        return $this->books()->wherePivot('onTrade', 1)->get();
    }

    //Trade Requests relationships
    public function pendingSentTradeRequests(): HasMany
    {
        return $this->hasMany(TradeRequest::class, foreignKey: 'sender_id')->where('response', value: null)->with(['receiver','requestedBook','proposedBook']);
    }

    public function pendingReceivedTradeRequests(): HasMany
    {
       return $this->hasMany(TradeRequest::class, foreignKey: 'receiver_id')->where('response',value: null)->with(['sender', 'requestedBook', 'proposedBook']);
    }


    //Loan Requests Relationships
    public function pendingSentLoanRequests(): HasMany
    {
        return $this->hasMany(LoanRequest::class, foreignKey: 'sender_id')->where('response', value: null)->with(['receiver','requestedBook']);
    }
    public function pendingReceivedLoanRequests():HasMany
    {
        return $this->hasMany(LoanRequest::class, foreignKey: 'receiver_id')->where('response',value: null)->with(['sender', 'requestedBook']);
    }
}
