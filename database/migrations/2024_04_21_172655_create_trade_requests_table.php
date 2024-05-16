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
        Schema::create('trade_requests', function (Blueprint $table) {
            $table->foreignIdFor(User::class,'receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class,'sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Book::class,'proposed_book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignIdFor(Book::class,'requested_book_id')->constrained('books')->cascadeOnDelete();
            $table->boolean('response')->nullable()->default(null);
            $table->timestamps();
            $table->primary(['receiver_id', 'sender_id', 'proposed_book_id', 'requested_book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_requests');
    }
};
