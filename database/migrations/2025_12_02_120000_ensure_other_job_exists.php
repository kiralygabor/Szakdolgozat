<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure 'Other' job exists
        $exists = DB::table('jobs')->where('name', 'Other')->exists();
        
        if (!$exists) {
            // We need a category for the job. 
            // Let's see if there's an 'Other' category or similar, or just pick the first one or create one.
            // Ideally 'Other' job might belong to a generic category or be available for all.
            // For now, let's check if 'Other' category exists, if not create it.
            
            $categoryId = DB::table('categories')->where('name', 'Other')->value('id');
            
            if (!$categoryId) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => 'Other',
                    'description' => 'Miscellaneous tasks that don\'t fit other categories',
                    'image_url' => null
                ]);
            }
            
            DB::table('jobs')->insert([
                'name' => 'Other',
                'categories_id' => $categoryId,
                'description' => 'Other services not listed'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't necessarily want to delete it as it might be used.
    }
};
