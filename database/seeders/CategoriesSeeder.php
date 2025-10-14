<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Handyman', 'image_url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=60', 'description' => 'Skilled help for repairs, fixtures, and home improvements.'],
            ['name' => 'Furniture Assembly', 'image_url' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1600&q=60', 'description' => 'Flat-pack assembly and setup for any room.'],
            ['name' => 'Plumbing Help', 'image_url' => 'https://images.unsplash.com/photo-1581579188871-45ea61f2a0c8?auto=format&fit=crop&w=1600&q=60', 'description' => 'Fix leaks, unclog drains, and install fixtures.'],
            ['name' => 'Electrical Repairs', 'image_url' => 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=1600&q=60', 'description' => 'Switches, outlets, lighting, and troubleshooting.'],
            ['name' => 'Moving Help', 'image_url' => 'https://images.unsplash.com/photo-1582582621959-48d8f8f9c6c3?auto=format&fit=crop&w=1600&q=60', 'description' => 'Extra hands for packing, loading, and unloading.'],
            ['name' => 'Heavy Lifting', 'image_url' => 'https://images.unsplash.com/photo-1511735643442-503bb3bd3485?auto=format&fit=crop&w=1600&q=60', 'description' => 'Move bulky items safely and efficiently.'],
            ['name' => 'Grocery Delivery', 'image_url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31b?auto=format&fit=crop&w=1600&q=60', 'description' => 'Personal shopping and doorstep delivery.'],
            ['name' => 'Courier Services', 'image_url' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1600&q=60', 'description' => 'Fast delivery of documents and packages.'],
            ['name' => 'Home Cleaning', 'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=60', 'description' => 'Routine cleaning to keep your home fresh.'],
            ['name' => 'Deep Cleaning', 'image_url' => 'https://images.unsplash.com/photo-1584634731339-e1a2b93c5ca4?auto=format&fit=crop&w=1600&q=60', 'description' => 'Detailed top-to-bottom clean for tough messes.'],
            ['name' => 'Garden Care', 'image_url' => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1600&q=60', 'description' => 'Lawn mowing, trimming, and yard maintenance.'],
            ['name' => 'Window Washing', 'image_url' => 'https://images.unsplash.com/photo-1523419443863-1111a4e56f49?auto=format&fit=crop&w=1600&q=60', 'description' => 'Streak-free windows inside and out.'],
            ['name' => 'Pet Sitting', 'image_url' => 'https://images.unsplash.com/photo-1534361960057-19889db9621e?auto=format&fit=crop&w=1600&q=60', 'description' => 'Loving care for pets while you are away.'],
            ['name' => 'Babysitting', 'image_url' => 'https://images.unsplash.com/photo-1519820830317-1b9ee1f4424a?auto=format&fit=crop&w=1600&q=60', 'description' => 'Reliable childcare for days or evenings.'],
            ['name' => 'Math Tutor', 'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=60', 'description' => 'One-on-one help to master math concepts.'],
            ['name' => 'Language Lessons', 'image_url' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1600&q=60', 'description' => 'Practice and learn new languages with a tutor.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'image_url' => $category['image_url'] ?? null,
                    'description' => $category['description'] ?? null,
                ]
            );
        }
    }
}


