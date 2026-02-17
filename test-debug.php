<?php

// Simple debug script to test the task posting
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Testing database connection...\n";
    
    // Test categories table
    $categories = DB::table('categories')->get();
    echo "Categories count: " . $categories->count() . "\n";
    
    // Test jobs table
    $jobs = DB::table('jobs')->get();
    echo "Jobs count: " . $jobs->count() . "\n";
    
    // Find the "Other" job
    $otherJob = DB::table('jobs')->where('name', 'Other')->first();
    if ($otherJob) {
        echo "Other job found with ID: {$otherJob->id}\n";
    } else {
        echo "Other job NOT found\n";
    }
    
    // Check advertisements table structure
    $columns = DB::select("SHOW COLUMNS FROM advertisements");
    echo "\nAdvertisments table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
