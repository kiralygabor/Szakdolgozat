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
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('birthdate', 50)->nullable();  
            $table->string('phone_number', 50)->nullable();
            $table->string('county', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('email', 150);
            $table->string('password', 255);
            $table->datetime('updated_at');
            $table->datetime('created_at');
            $table->boolean('verified')->default(false);


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
