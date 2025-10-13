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
        Schema::create('advertisments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('reviews_id')->nullable();
            $table->unsignedBigInteger('employer_id');
            $table->unsignedBigInteger('employee_id');
            
            $table->char('title', 150);
            $table->text('description');
            $table->integer('price');
            $table->char('location', 150);
            $table->dateTime('created_at');
            $table->dateTime('expiration_date');
            $table->enum('status', ['open', 'matched', 'closed'])->default('open');
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('reviews_id')->references('id')->on('reviews')->onDelete('set null');
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisments');
    }
};
