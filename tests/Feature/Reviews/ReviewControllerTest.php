<?php

use App\Models\Book;
use App\Models\Review;
use App\Models\TradeRequest;
use App\Models\User;

it('it should render a page with all the reviews for a user', function ()
{
    $review = Review::factory()->create();

    $response = login()->get('/reviews/'.$review->reviewed->id);

    $response
        ->assertStatus(200)
        ->assertViewIs('reviews.show')
        ->assertViewHas('reviews');
});

it('should store a new review for a user', function ()
{
    $reviewer = User::factory()->create();
    $reviewed = User::factory()->create();

    TradeRequest::create([
        'sender_id' => $reviewer->id,
        'receiver_id' => $reviewed->id,
        'response' => 1,
        'proposed_book_id' => Book::factory()->create()->id,
        'requested_book_id' => Book::factory()->create()->id,
    ]);

    $response = login($reviewer)->post('/reviews/'.$reviewed->id, [
        'rating' => fake()->numberBetween(1, 5),
        'comment' => fake()->text()
    ]);

    $response
        ->assertStatus(302)
        ->assertRedirect('/reviews/'.$reviewed->id)
        ->assertSessionHas('message');

    expect(Review::latest()->first())
        ->rating->not->toBeNull()
        ->comment->not->toBeNull();
});

it('should not store a new review for a user due to an already existing review', function ()
{
    $review = Review::factory()->create();
    $reviewer = $review->reviewer;
    $reviewed = $review->reviewed;

    TradeRequest::create([
        'sender_id' => $reviewer->id,
        'receiver_id' => $reviewed->id,
        'response' => 1,
        'proposed_book_id' => Book::factory()->create()->id,
        'requested_book_id' => Book::factory()->create()->id,
    ]);

    $response = login($reviewer)->post('/reviews/'.$reviewed->id, [
        'rating' => fake()->numberBetween(1, 5),
        'comment' => fake()->text()
    ]);

    $response
        ->assertStatus(302)
        ->assertRedirect('/reviews/'.$review->reviewed->id)
        ->assertSessionHas('message', 'You have already submitted a review for this user!');
});

it('should not store a new review for a user with which there are no transactions', function ()
{
    $reviewer = User::factory()->create();
    $reviewed = User::factory()->create();

    $response = login($reviewer)->post('/reviews/'.$reviewed->id, [
        'rating' => fake()->numberBetween(1, 5),
        'comment' => fake()->text()
    ]);

    expect($response)
        ->assertStatus(403);
});
