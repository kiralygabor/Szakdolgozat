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

            // category (legacy column name used across the project)
            $table->unsignedBigInteger('categories_id')->nullable();

            // optional review attached to an advert
            $table->unsignedBigInteger('reviews_id')->nullable();

            $table->unsignedBigInteger('jobs_id')->nullable();


            // who posted the advert (employer) and optionally assigned employee
            $table->unsignedBigInteger('employer_id');
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->string('title', 150);
            $table->text('description');
            $table->integer('price');
            $table->string('location', 150)->nullable();

            // task scheduling / preferences
            $table->date('required_date')->nullable();
            $table->date('required_before_date')->nullable();
            $table->boolean('is_date_flexible')->default(false);
            $table->string('preferred_time')->nullable();
            $table->enum('task_type', ['in-person', 'online'])->default('in-person');

            // photos stored as JSON array of paths
            $table->json('photos')->nullable();

            // created_at / updated_at
            $table->timestamps();

            // expiration datetime for task listing
            $table->dateTime('expiration_date')->nullable();

            $table->enum('status', ['open', 'matched', 'closed'])->default('open');

            // foreign keys
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('jobs_id')->references('id')->on('jobs')->onDelete('set null');
            $table->foreign('reviews_id')->references('id')->on('reviews')->onDelete('set null');
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('set null');
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
