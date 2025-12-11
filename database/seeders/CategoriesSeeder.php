<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Home Services', 'image_url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=60', 'description' => 'Skilled professionals who can assist with home repairs, installations, renovations, and general improvements. From quick fixes to major upgrades, get trusted help to keep your home running smoothly.'],
            ['name' => 'Cleaning & Maintenance', 'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=60', 'description' => 'Comprehensive cleaning and routine maintenance services for homes and businesses. Ideal for keeping your space fresh, hygienic, and well-maintained on a regular or one-off basis.'],
            ['name' => 'Moving & Delivery', 'image_url' => 'https://images.unsplash.com/photo-1581579188871-45ea61f2a0c8?auto=format&fit=crop&w=1600&q=60', 'description' => 'Reliable movers and delivery experts who can help transport furniture, appliances, and personal items safely. Perfect for relocations, small moves, or urgent item deliveries.'],
            ['name' => 'Personal Care & Wellness', 'image_url' => 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=1600&q=60', 'description' => 'Supportive services focused on personal well-being, including grooming, wellness routines, and lifestyle assistance. Ideal for maintaining a balanced and healthy daily routine.'],
            ['name' => 'Automotive', 'image_url' => 'https://plus.unsplash.com/premium_photo-1674375348357-a25140a68bbd?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'description' => 'Skilled automotive professionals providing assistance with car maintenance, detailing, inspections, minor repairs, and more. Keep your vehicle clean, safe, and running efficiently.'],
            ['name' => 'Trades & Construction', 'image_url' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1600&q=60', 'description' => 'Licensed and experienced tradespeople offering construction, renovations, carpentry, electrical, plumbing, and other skilled services. Ideal for both small residential tasks and large building projects.'],
            ['name' => 'Food & Catering', 'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=60', 'description' => 'Delicious food preparation and catering services for events, celebrations, or everyday meals. Whether casual or formal, enjoy professionally prepared dishes delivered with care.'],
            ['name' => 'Events & Entertainment', 'image_url' => 'https://images.unsplash.com/photo-1584634731339-e1a2b93c5ca4?auto=format&fit=crop&w=1600&q=60', 'description' => 'Support for planning, hosting, and enhancing events of all sizes. Includes entertainment, setup, coordination, and more to make every occasion memorable and stress-free.'],
            ['name' => 'Lessons & Education', 'image_url' => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1600&q=60', 'description' => 'One-on-one tutoring, skill training, and educational support across many subjects and specialties. Designed to help learners of all ages grow and achieve academic or personal goals.'],
            ['name' => 'Pet Services', 'image_url' => 'https://images.unsplash.com/photo-1523419443863-1111a4e56f49?auto=format&fit=crop&w=1600&q=60', 'description' => 'Reliable, caring assistance for your pets, including sitting, grooming, walking, feeding, and general companionship. Perfect for busy schedules, travel, or daily support.'],
            ['name' => 'Gardening & Outdoor', 'image_url' => 'https://images.unsplash.com/photo-1534361960057-19889db9621e?auto=format&fit=crop&w=1600&q=60', 'description' => 'Maintenance and enhancement of outdoor spaces, including lawn mowing, garden care, landscaping, and yard cleanups. Keep your outdoor areas healthy, tidy, and beautiful year-round.'],
            ['name' => 'Home Lifestyle', 'image_url' => 'https://images.unsplash.com/photo-1519820830317-1b9ee1f4424a?auto=format&fit=crop&w=1600&q=60', 'description' => 'Convenient services designed to simplify daily living—childcare, home organization, errands, shopping assistance, and more. Ideal for families and individuals seeking everyday support.'],
            ['name' => 'Miscellaneous', 'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=60', 'description' => 'For all tasks that don’t quite fit into a specific category—creative projects, last-minute help, custom requests, or unique one-off tasks. Flexible support for anything you need.'],
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


