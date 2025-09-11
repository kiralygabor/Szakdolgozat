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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('categories_id');
            $table->integer('services_id');
            $table->integer('city_id');
            $table->char('name', 100);
            $table->char('email', 150);
            $table->char('password_hash', 255);
            $table->boolean('is_active');
            $table->char('phone', 20);
            $table->unsignedInteger('created_at');
            $table->date('birthdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
