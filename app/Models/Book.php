<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'ISBN';

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}