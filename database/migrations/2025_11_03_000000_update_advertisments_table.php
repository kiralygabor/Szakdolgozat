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
        Schema::table('advertisments', function (Blueprint $table) {
            // add categories_id if missing
            if (!Schema::hasColumn('advertisments', 'categories_id')) {
                // create nullable categories_id; adding a foreign key can fail if legacy data contains invalid ids
                $table->unsignedBigInteger('categories_id')->after('id')->nullable();
            }

            if (!Schema::hasColumn('advertisments', 'employee_id')) {
                // nullable employee_id to avoid constraint issues on existing records
                $table->unsignedBigInteger('employee_id')->nullable()->after('employer_id');
            }

            if (!Schema::hasColumn('advertisments', 'required_date')) {
                $table->date('required_date')->nullable()->after('expiration_date');
            }
            if (!Schema::hasColumn('advertisments', 'required_before_date')) {
                $table->date('required_before_date')->nullable()->after('required_date');
            }
            if (!Schema::hasColumn('advertisments', 'is_date_flexible')) {
                $table->boolean('is_date_flexible')->default(false)->after('required_before_date');
            }
            if (!Schema::hasColumn('advertisments', 'preferred_time')) {
                $table->string('preferred_time')->nullable()->after('is_date_flexible');
            }
            if (!Schema::hasColumn('advertisments', 'task_type')) {
                $table->string('task_type')->default('in-person')->after('preferred_time');
            }
            if (!Schema::hasColumn('advertisments', 'photos')) {
                $table->json('photos')->nullable()->after('task_type');
            }
            if (!Schema::hasColumn('advertisments', 'expiration_date')) {
                $table->dateTime('expiration_date')->nullable()->after('photos');
            }
            // add timestamps individually if missing to avoid adding a column twice
            if (!Schema::hasColumn('advertisments', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('expiration_date');
            }
            if (!Schema::hasColumn('advertisments', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisments', function (Blueprint $table) {
            // attempt to drop columns if they exist
            if (Schema::hasColumn('advertisments', 'photos')) {
                $table->dropColumn('photos');
            }
            if (Schema::hasColumn('advertisments', 'preferred_time')) {
                $table->dropColumn('preferred_time');
            }
            if (Schema::hasColumn('advertisments', 'is_date_flexible')) {
                $table->dropColumn('is_date_flexible');
            }
            if (Schema::hasColumn('advertisments', 'required_before_date')) {
                $table->dropColumn('required_before_date');
            }
            if (Schema::hasColumn('advertisments', 'required_date')) {
                $table->dropColumn('required_date');
            }
            if (Schema::hasColumn('advertisments', 'task_type')) {
                $table->dropColumn('task_type');
            }
            // Note: we avoid dropping categories_id/employee_id/expiration_date/timestamps in down to be cautious about existing data
        });
    }
};
