<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();

            $table->string('account_id', 15)->unique();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('birthdate', 50)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('avatar')->nullable();
            $table->string('email', 150);
            $table->string('password', 255);
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->boolean('verified')->default(false);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('email_task_digest')->default(false);
            $table->boolean('email_direct_quotes')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
