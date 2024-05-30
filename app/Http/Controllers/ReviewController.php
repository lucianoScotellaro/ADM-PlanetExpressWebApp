<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function show(User $user)
    {
        return view('reviews.show', ['reviews'=>$user->reviews]);
    }

    public function store(User $reviewedUser)
    {
        $validatedData = request()->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['string', 'nullable', 'max:255']
        ]);

        try
        {
            Review::create([
                'reviewer_id' => Auth::user()->id,
                'reviewed_id' => $reviewedUser->id,
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment']
            ]);

            $oldAverage = $reviewedUser->averageRating;
            $newAverage = ($oldAverage*($reviewedUser->reviews()->count()-1) + $validatedData['rating'])/($reviewedUser->reviews()->count());

            User::where('id',$reviewedUser->id)->update(['averageRating' => $newAverage]);

            $message = 'Your review has been submitted correctly!';
        }
        catch (\Exception $exception)
        {
            $message = 'You have already submitted a review for this user!';
        }
        return redirect('/reviews/'.$reviewedUser->id)->with('message', $message);
    }
}
