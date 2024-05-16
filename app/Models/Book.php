<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Config;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function searchOn(array $parameters, String $searchOn): Collection
    {
        $resultsPerPage = Config::get('constants.resultsPerPage');
        //Selects books that match searching parameters
        return self::search($parameters)
            //Selects books that have at least one proposer
            ->whereIn('id', function ($query) use ($searchOn) {
                if ($searchOn == 'proposedBook') {
                    $query->select('book_id')
                        ->from('book_user')
                        ->where('user_id', '!=', auth()->id());
                } elseif ($searchOn == 'requestedBook') {
                    $query->select('book_id')
                        ->from('book_user')
                        ->where('user_id', '!=', auth()->id())
                        ->where('onWishlist', '=', 1);
                }
            })
            //Handles pagination
            ->offset(($parameters['pageNumber'] - 1) * $resultsPerPage)
            ->limit($resultsPerPage)
            ->get();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function proposers(): BelongsToMany
    {
        return $this->users()
            ->where('onLoan','=',1, 'or')
            ->where('onTrade','=',1)
            ->groupBy('user_id');
    }

    private static function search(array $parameters)
    {
        return Book::select()
            ->where('title','like', $parameters['title'] != null ? '%'.$parameters['title'].'%' : '%%')
            ->where('author','like',$parameters['author'] != null ? '%'.$parameters['author'].'%' : '%%')
            ->where('category','like',$parameters['category'] != null ? '%'.$parameters['category'].'%' : '%%');
    }
}
