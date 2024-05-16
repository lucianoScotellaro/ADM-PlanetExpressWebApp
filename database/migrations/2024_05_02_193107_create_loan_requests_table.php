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
        Schema::create('loan_requests', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Book::class, 'requested_book_id')->constrained('books')->cascadeOnDelete();
            $table->date('expiration')->nullable()->default(null);
            $table->boolean('response')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_requests');
    }
};
