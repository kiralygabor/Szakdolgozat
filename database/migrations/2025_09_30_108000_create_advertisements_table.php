<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobs_id')->nullable()->index()->constrained('jobs')->nullOnDelete();
            $table->foreignId('employer_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviews_id')->nullable()->constrained('reviews')->nullOnDelete();

            $table->string('title', 150);
            $table->text('description');
            $table->integer('price');
            $table->string('location', 150)->nullable();
            
            $table->date('required_date')->nullable();
            $table->date('required_before_date')->nullable();
            $table->boolean('is_date_flexible')->default(false);
            $table->string('preferred_time')->nullable();
            $table->string('task_type')->default('in-person');
            $table->json('photos')->nullable();
            $table->dateTime('expiration_date')->nullable();
            
            $table->string('status')->index()->default('open');
            $table->integer('views')->default(0);
            $table->boolean('is_direct')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
