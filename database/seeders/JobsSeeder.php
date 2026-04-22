<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
 
class JobsSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = DB::table('categories')
            ->get()
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [Str::lower($name) => $id])
            ->toArray();
 
        $jobs = [
            ["Accounting", "Professional bookkeeping, financial records help, and account management.", "Home Services"],
            ["Admin", "General administrative assistance for offices or personal tasks.", "Home Services"],
            ["Alterations", "Clothing adjustments, resizing, and tailoring services.", "Home Services"],
            ["Appliances", "Help with installing, repairing, or troubleshooting home appliances.", "Home Services"],
            ["Architects", "Architectural planning, layout drafting, and design assistance.", "Trades & Construction"],
            ["Assembly", "General assembly support for furniture and household items.", "Cleaning & Maintenance"],
            ["Bakers", "Cake, pastry, and bread preparation for events or personal orders.", "Food & Catering"],
            ["Barbers", "Professional haircuts and grooming services.", "Personal Care & Wellness"],
            ["Bathroom Renovation", "Bathroom upgrades, fixture installation, and remodeling support.", "Home Services"],
            ["Beauticians", "Beauty treatments, skincare, and makeup services.", "Personal Care & Wellness"],
            ["Bicycle Service", "Bike repairs, tuning, and maintenance assistance.", "Automotive"],
            ["Bricklayer", "Bricklaying and masonry work for construction projects.", "Trades & Construction"],
            ["Building & Construction", "General construction tasks, repairs, and structural work.", "Trades & Construction"],
            ["Business", "Business support tasks, consulting, and administrative help.", "Home Services"],
            ["Car Detailing", "Interior and exterior car cleaning and detailing.", "Automotive"],
            ["Car Repair", "Minor automotive repair and troubleshooting services.", "Automotive"],
            ["Car Service", "Support with general vehicle checkups and maintenance.", "Automotive"],
            ["Car Wash", "Professional car washing services at home or on-site.", "Automotive"],
            ["Carpentry", "Woodwork, repairs, and custom furniture-related tasks.", "Trades & Construction"],
            ["Carpet Cleaning", "Deep carpet cleaning, stain removal, and refresh services.", "Cleaning & Maintenance"],
            ["Catering", "Food preparation and serving for events or gatherings.", "Food & Catering"],
            ["Chef", "Personal chef services for meals, events, or meal prep.", "Food & Catering"],
            ["Childcare & Safety", "Supervision and care for children with focus on safety.", "Home Lifestyle"],
            ["Cladding", "Exterior cladding installation and repair services.", "Trades & Construction"],
            ["Cleaning", "General cleaning services for homes or offices.", "Cleaning & Maintenance"],
            ["Clearance Services", "Decluttering, removing unwanted items, and clearing spaces.", "Moving & Delivery"],
            ["Coaching", "Support with personal development, skills, or motivation.", "Lessons & Education"],
            ["Computers & IT", "Technical support, troubleshooting, and IT setup.", "Miscellaneous"],
            ["Concreting", "Concrete pouring, leveling, and small construction projects.", "Trades & Construction"],
            ["Cooking", "Meal preparation services for individuals or families.", "Food & Catering"],
            ["Counselling & Therapy", "Personal guidance, wellness, and emotional support sessions.", "Personal Care & Wellness"],
            ["Courier Services", "Fast delivery of small packages and documents.", "Moving & Delivery"],
            ["Dance Lessons", "Personal or group dance instruction.", "Lessons & Education"],
            ["Decking", "Deck installation, repairs, and refinishing.", "Trades & Construction"],
            ["Delivery", "General item pickup and delivery services.", "Moving & Delivery"],
            ["Design", "Graphic, interior, or general design support.", "Miscellaneous"],
            ["Draftsman", "Technical drawings and plans for architectural projects.", "Trades & Construction"],
            ["Driving", "Driving assistance for errands, trips, or transport.", "Moving & Delivery"],
            ["Electricians", "Electrical troubleshooting, repairs, and installations.", "Personal Care & Wellness"],
            ["Engraving", "Custom engraving on metal, wood, or personal items.", "Home Services"],
            ["Entertainment", "Entertainment assistance for events and gatherings.", "Events & Entertainment"],
            ["Events", "Event setup, coordination, and support services.", "Events & Entertainment"],
            ["Fencing", "Fence installation, repair, and maintenance.", "Trades & Construction"],
            ["Fitness", "Personal training and fitness coaching.", "Personal Care & Wellness"],
            ["Flooring", "Floor installation, repairs, and refinishing.", "Trades & Construction"],
            ["Florist", "Flower arrangement and floral decoration services.", "Food & Catering"],
            ["Flower Delivery", "Delivering fresh flowers for any occasion.", "Food & Catering"],
            ["Food Delivery", "Pickup and delivery of meals from restaurants or stores.", "Food & Catering"],
            ["Furniture Assembly", "Professional furniture assembly for any room.", "Cleaning & Maintenance"],
            ["Gardening", "General gardening help including planting and trimming.", "Gardening & Outdoor"],
            ["Gate Installation", "Installation and repair of home gates or entryways.", "Trades & Construction"],
            ["Grocery Delivery", "Personal shopping and delivery of groceries.", "Food & Catering"],
            ["Hairdressers", "Hair styling, cutting, and grooming services.", "Personal Care & Wellness"],
            ["Handyman", "General home repairs, fixes, and improvements.", "Home Services"],
            ["Health & Wellness", "Wellness coaching and lifestyle support services.", "Personal Care & Wellness"],
            ["Heating & Cooling", "HVAC support, thermostat installs, and vent maintenance.", "Trades & Construction"],
            ["Home & Lifestyle", "General lifestyle and home-support tasks.", "Home Lifestyle"],
            ["Home Automation and Security", "Smart home and security system setup.", "Home Services"],
            ["Home Theatre", "Home theater setup, wiring, and configuration assistance.", "Home Services"],
            ["House Cleaning", "Routine home cleaning and tidying tasks.", "Cleaning & Maintenance"],
            ["Interior Designer", "Interior decorating and space-planning assistance.", "Home Lifestyle"],
            ["Kitchen Renovation", "Kitchen upgrades, fixture installation, and remodeling help.", "Home Services"],
            ["Landscaping", "Lawn shaping, planting, and outdoor design.", "Gardening & Outdoor"],
            ["Laundry", "Laundry washing, folding, and organizing assistance.", "Cleaning & Maintenance"],
            ["Lawn Care", "Lawn mowing, edging, and general yard upkeep.", "Gardening & Outdoor"],
            ["Legal Services", "Legal paperwork and administrative help.", "Miscellaneous"],
            ["Lessons", "General tutoring and training services.", "Lessons & Education"],
            ["Locksmith", "Lock repair, key cutting, and home entry assistance.", "Home Services"],
            ["Makeup Artist", "Professional makeup services for events or photoshoots.", "Personal Care & Wellness"],
            ["Marketing", "Marketing support, branding, and promotion tasks.", "Miscellaneous"],
            ["Martial Arts", "Private or group martial arts training.", "Lessons & Education"],
            ["Mechanic", "General automotive repair and mechanical troubleshooting.", "Automotive"],
            ["Modelling", "Modeling assistance for photoshoots or events.", "Home Lifestyle"],
            ["Motorcycle Mechanic", "Motorcycle repair and maintenance services.", "Automotive"],
            ["Music Lessons", "Instrument or vocal coaching and instruction.", "Lessons & Education"],
            ["Painting", "Interior and exterior painting services.", "Trades & Construction"],
            ["Paving", "Brick and concrete paving installation and maintenance.", "Trades & Construction"],
            ["Pest Control", "Inspection and treatment for common pests.", "Home Services"],
            ["Pet Care", "General pet care such as feeding and walking.", "Pet Services"],
            ["Photographers", "Photography for portraits, events, or products.", "Events & Entertainment"],
            ["Plasterer", "Plastering walls, ceilings, and surface smoothing.", "Trades & Construction"],
            ["Plumbing", "General plumbing repairs and installations.", "Moving & Delivery"],
            ["Pool Maintenance", "Pool cleaning, chemical balancing, and upkeep.", "Cleaning & Maintenance"],
            ["Real Estate", "Support tasks related to property listings and visits.", "Miscellaneous"],
            ["Removals", "Assistance with moving, packing, and lifting.", "Moving & Delivery"],
            ["Roofing", "Roof repair, inspection, and maintenance work.", "Trades & Construction"],
            ["Rubbish Removal", "Pickup and disposal of household or yard waste.", "Moving & Delivery"],
            ["Sharpening", "Tool and knife sharpening services.", "Miscellaneous"],
            ["Surveyors", "Property measurement and survey assistance.", "Trades & Construction"],
            ["Swimming Lessons", "Personal swimming instruction for all ages.", "Lessons & Education"],
            ["Tailors", "Custom tailoring, adjustments, and clothing repairs.", "Home Services"],
            ["Tattoo Artists", "Professional tattoo design and application.", "Personal Care & Wellness"],
            ["Tiling", "Tile installation, repair, and alignment.", "Trades & Construction"],
            ["Tradesman", "General trade skills and handyman services.", "Trades & Construction"],
            ["Translation", "Translation services for documents or conversations.", "Lessons & Education"],
            ["Tutoring", "Academic tutoring and study support.", "Lessons & Education"],
            ["Vehicle Transport", "Transporting vehicles safely and efficiently.", "Moving & Delivery"],
            ["Wall Hanging and Mounting", "Mounting shelves, TVs, and wall décor.", "Home Services"],
            ["Wallpapering", "Applying, removing, and repairing wallpaper.", "Trades & Construction"],
            ["Waste Collection & Disposal", "Responsible waste removal and disposal services.", "Moving & Delivery"],
            ["Waterproofing", "Waterproofing walls, floors, and surfaces.", "Trades & Construction"],
            ["Web", "Web design, setup, or support tasks.", "Miscellaneous"],
            ["Wedding", "Wedding assistance, setup, and coordination.", "Events & Entertainment"],
            ["Wheel & Tyre Service", "Tire changes, balancing, and minor wheel services.", "Automotive"],
            ["Window Cleaning", "Streak-free window cleaning inside and outside.", "Cleaning & Maintenance"],
            ["Writing", "Writing help for content, letters, or documentation.", "Miscellaneous"],
        ];
 
        foreach ($jobs as $job) {
            $categoryName = Str::lower($job[2]);
 
            DB::table('jobs')->insert([
                'name' => $job[0],
                'description' => $job[1],
                'categories_id' => $categoryMap[$categoryName] ?? null,
            ]);
        }
    }
}