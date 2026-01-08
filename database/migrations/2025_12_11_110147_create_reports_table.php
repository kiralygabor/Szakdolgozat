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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisments')->onDelete('cascade');
            $table->text('description');
            $table->string('reporter_account_id');
            $table->foreign('reporter_account_id')
                ->references('account_id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('reported_account_id');
            $table->foreign('reported_account_id')
                ->references('account_id')
                ->on('users')
                ->onDelete('cascade');

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
