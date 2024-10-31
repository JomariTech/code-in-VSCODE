<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('rate_review_and_comments', function (Blueprint $table) {

            // Add a new column 'smartphone_id' after the 'comment' column
            $table->unsignedBigInteger('smartphone_id')->after('comment');
            
            // Set up a foreign key constraint on 'smartphone_id' that references 'id' on 'smartphones' table
            // if a smartphone is deleted, related records in 'rate_review_and_comments' will also be deleted
            $table->foreign('smartphone_id')->references('id')->on('smartphones')->onDelete('cascade');
        });
    }

    
    public function down(): void
    {
        Schema::table('rate_review_and_comments', function (Blueprint $table) {

            // drop the foreign key constraint on 'smartphone_id'
            $table->dropForeign(['smartphone_id']); 

            // drop the 'smartphone_id' column
            $table->dropColumn('smartphone_id'); 
        });
    }
};
