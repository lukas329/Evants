<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // User who leaves the review
            $table->foreignId('reviewed_id')->constrained('users')->onDelete('cascade'); // User being reviewed
            $table->integer('rating')->default(1); // 1-5 star rating
            $table->text('comment')->nullable(); // Optional comment
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
