<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('smartphones', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('brands');
            $table->string('model');
            $table->string('overview');
            $table->string('processor');
            $table->string('memory');
            $table->string('display');
            $table->string('battery');
            $table->string('camera');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('smartphones');
    }
};
