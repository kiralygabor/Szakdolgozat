<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('penalty_id')->constrained('penalties')->cascadeOnDelete();
            $table->dateTime('expiration_date');
            $table->text('comment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklist');
    }
};
