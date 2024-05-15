<?php

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

            $table->string("id")->primary();
            $table->string('title');
            $table->string('author')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('category')->nullable()->default(null);
            $table->string('publishedDate')->nullable()->default(null);
            $table->string('thumbnailUrl')->nullable()->default(null);
            $table->integer('pageCount')->nullable()->default(null);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
