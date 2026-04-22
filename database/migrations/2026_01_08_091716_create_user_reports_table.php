<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            
            $table->string('reporter_account_id');
            $table->foreign('reporter_account_id')
                ->references('account_id')
                ->on('users')
                ->cascadeOnDelete();

            $table->string('reported_account_id');
            $table->foreign('reported_account_id')
                ->references('account_id')
                ->on('users')
                ->cascadeOnDelete();

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reports');
    }
};
