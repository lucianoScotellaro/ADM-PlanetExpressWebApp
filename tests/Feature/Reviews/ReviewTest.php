<?php

use App\Models\Review;
use App\Models\User;

it('should return the creator and the target of a review', function ()
{
    $reviewer = User::factory()->create();
    $reviewed = User::factory()->create();

    $review = Review::create([
        'reviewer_id' => $reviewer->id,
        'reviewed_id' => $reviewed->id,
        'rating' => fake()->numberBetween(1, 5),
        'comment' => fake()->text()
    ]);

    expect($review)
        ->reviewer->id->toBe($reviewer->id)
        ->reviewed->id->toBe($reviewed->id);
});
