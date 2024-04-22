<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->integer('ISBN')->primary();
            $table->string('title');
            $table->string('author');
            $table->timestamps();
        });

        Schema::create('book_user', function (Blueprint $table) {
           $table->foreignIdFor(User::class);
           $table->foreignIdFor(Book::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_user');
    }
};
