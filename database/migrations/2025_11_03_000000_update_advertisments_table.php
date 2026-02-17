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
        Schema::table('advertisements', function (Blueprint $table) {
            // add categories_id if missing
            if (!Schema::hasColumn('advertisements', 'categories_id')) {
                // create nullable categories_id; adding a foreign key can fail if legacy data contains invalid ids
                $table->unsignedBigInteger('categories_id')->after('id')->nullable();
            }

            if (!Schema::hasColumn('advertisements', 'employee_id')) {
                // nullable employee_id to avoid constraint issues on existing records
                $table->unsignedBigInteger('employee_id')->nullable()->after('employer_id');
            }

            if (!Schema::hasColumn('advertisements', 'required_date')) {
                $table->date('required_date')->nullable()->after('expiration_date');
            }
            if (!Schema::hasColumn('advertisements', 'required_before_date')) {
                $table->date('required_before_date')->nullable()->after('required_date');
            }
            if (!Schema::hasColumn('advertisements', 'is_date_flexible')) {
                $table->boolean('is_date_flexible')->default(false)->after('required_before_date');
            }
            if (!Schema::hasColumn('advertisements', 'preferred_time')) {
                $table->string('preferred_time')->nullable()->after('is_date_flexible');
            }
            if (!Schema::hasColumn('advertisements', 'task_type')) {
                $table->string('task_type')->default('in-person')->after('preferred_time');
            }
            if (!Schema::hasColumn('advertisements', 'photos')) {
                $table->json('photos')->nullable()->after('task_type');
            }
            if (!Schema::hasColumn('advertisements', 'expiration_date')) {
                $table->dateTime('expiration_date')->nullable()->after('photos');
            }
            // add timestamps individually if missing to avoid adding a column twice
            if (!Schema::hasColumn('advertisements', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('expiration_date');
            }
            if (!Schema::hasColumn('advertisements', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            // attempt to drop columns if they exist
            if (Schema::hasColumn('advertisements', 'photos')) {
                $table->dropColumn('photos');
            }
            if (Schema::hasColumn('advertisements', 'preferred_time')) {
                $table->dropColumn('preferred_time');
            }
            if (Schema::hasColumn('advertisements', 'is_date_flexible')) {
                $table->dropColumn('is_date_flexible');
            }
            if (Schema::hasColumn('advertisements', 'required_before_date')) {
                $table->dropColumn('required_before_date');
            }
            if (Schema::hasColumn('advertisements', 'required_date')) {
                $table->dropColumn('required_date');
            }
            if (Schema::hasColumn('advertisements', 'task_type')) {
                $table->dropColumn('task_type');
            }
            // Note: we avoid dropping categories_id/employee_id/expiration_date/timestamps in down to be cautious about existing data
        });
    }
};
