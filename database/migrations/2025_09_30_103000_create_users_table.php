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
    $table->unsignedBigInteger('city_id')->nullable();
    $table->unsignedBigInteger('subscription_id')->nullable();

    $table->string('account_id', 15)->index();
    $table->string('first_name', 50)->nullable();
    $table->string('last_name', 50)->nullable();
    $table->string('birthdate', 50)->nullable();  
    $table->string('phone_number', 50)->nullable();
    $table->string('email', 150);
    $table->string('password', 255);
    $table->timestamps();
    $table->boolean('verified')->default(false);

    $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
    $table->foreign('subscription_id')->references('id')->on('subscription')->onDelete('set null');
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
