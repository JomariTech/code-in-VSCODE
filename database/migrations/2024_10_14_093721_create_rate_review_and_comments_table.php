<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('rate_review_and_comments', function (Blueprint $table) {
            $table->id();
            $table->string('username'); // Column for the username
            $table->integer('rating'); // Column for the rating
            $table->text('comment'); // Column for the comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_review_and_comments');
    }
};
