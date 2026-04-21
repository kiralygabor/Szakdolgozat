<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Home Services', 'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=80', 'description' => 'Skilled professionals who can assist with home repairs, installations, renovations, and general improvements. From quick fixes to major upgrades, get trusted help to keep your home running smoothly.'],
            ['name' => 'Cleaning & Maintenance', 'image_url' => 'https://images.unsplash.com/photo-1628177142898-93e36e4e3a50?auto=format&fit=crop&w=1600&q=80', 'description' => 'Comprehensive cleaning and routine maintenance services for homes and businesses. Ideal for keeping your space fresh, hygienic, and well-maintained on a regular or one-off basis.'],
            ['name' => 'Moving & Delivery', 'image_url' => 'https://images.unsplash.com/photo-1600518464441-9154a4dea21b?auto=format&fit=crop&w=1600&q=80', 'description' => 'Reliable movers and delivery experts who can help transport furniture, appliances, and personal items safely. Perfect for relocations, small moves, or urgent item deliveries.'],
            ['name' => 'Personal Care & Wellness', 'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1600&q=80', 'description' => 'Supportive services focused on personal well-being, including grooming, wellness routines, and lifestyle assistance. Ideal for maintaining a balanced and healthy daily routine.'],
            ['name' => 'Automotive', 'image_url' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=1600&q=80', 'description' => 'Skilled automotive professionals providing assistance with car maintenance, detailing, inspections, minor repairs, and more. Keep your vehicle clean, safe, and running efficiently.'],
            ['name' => 'Trades & Construction', 'image_url' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80', 'description' => 'Licensed and experienced tradespeople offering construction, renovations, carpentry, electrical, plumbing, and other skilled services. Ideal for both small residential tasks and large building projects.'],
            ['name' => 'Food & Catering', 'image_url' => 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=1600&q=80', 'description' => 'Delicious food preparation and catering services for events, celebrations, or everyday meals. Whether casual or formal, enjoy professionally prepared dishes delivered with care.'],
            ['name' => 'Events & Entertainment', 'image_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1600&q=80', 'description' => 'Support for planning, hosting, and enhancing events of all sizes. Includes entertainment, setup, coordination, and more to make every occasion memorable and stress-free.'],
            ['name' => 'Lessons & Education', 'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1600&q=80', 'description' => 'One-on-one tutoring, skill training, and educational support across many subjects and specialties. Designed to help learners of all ages grow and achieve academic or personal goals.'],
            ['name' => 'Pet Services', 'image_url' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=1600&q=80', 'description' => 'Reliable, caring assistance for your pets, including sitting, grooming, walking, feeding, and general companionship. Perfect for busy schedules, travel, or daily support.'],
            ['name' => 'Gardening & Outdoor', 'image_url' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=1600&q=80', 'description' => 'Maintenance and enhancement of outdoor spaces, including lawn mowing, garden care, landscaping, and yard cleanups. Keep your outdoor areas healthy, tidy, and beautiful year-round.'],
            ['name' => 'Home Lifestyle', 'image_url' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1600&q=80', 'description' => 'Convenient services designed to simplify daily living—childcare, home organization, errands, shopping assistance, and more. Ideal for families and individuals seeking everyday support.'],
            ['name' => 'Miscellaneous', 'image_url' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=1600&q=80', 'description' => 'For all tasks that don\'t quite fit into a specific category—creative projects, last-minute help, custom requests, or unique one-off tasks. Flexible support for anything you need.'],
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


