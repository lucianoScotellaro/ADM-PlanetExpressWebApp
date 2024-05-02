<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();

        Book::factory(10)->create()->each(function (Book $book) {
            $book->users()->attach(1, ['onLoan'=>fake()->boolean()]);
        });

        Book::factory(10)->create()->each(function (Book $book) {
           $book->users()->attach(2, ['onLoan'=>fake()->boolean()]);
        });
    }
}
