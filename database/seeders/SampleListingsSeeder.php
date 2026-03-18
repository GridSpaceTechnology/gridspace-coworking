<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\User;
use App\Models\Category;
use App\Models\City;
use Illuminate\Support\Str;

class SampleListingsSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create sample users
        $host1 = User::where('email', 'host1@example.com')->first();
        $host2 = User::where('email', 'host2@example.com')->first();
        $host3 = User::where('email', 'host3@example.com')->first();

        if (!$host1) {
            $host1 = User::create([
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'host1@example.com',
                'phone' => '+2348000000001',
                'password' => bcrypt('password'),
                'role' => 'host',
                'approved' => true,
            ]);
        }

        if (!$host2) {
            $host2 = User::create([
                'firstname' => 'Jane',
                'lastname' => 'Smith',
                'email' => 'host2@example.com',
                'phone' => '+2348000000002',
                'password' => bcrypt('password'),
                'role' => 'host',
                'approved' => true,
            ]);
        }

        if (!$host3) {
            $host3 = User::create([
                'firstname' => 'Mike',
                'lastname' => 'Johnson',
                'email' => 'host3@example.com',
                'phone' => '+2348000000003',
                'password' => bcrypt('password'),
                'role' => 'host',
                'approved' => true,
            ]);
        }

        // Get categories and cities, create if they don't exist
        $coworking = Category::where('name', 'Coworking Space')->first();
        if (!$coworking) {
            $coworking = Category::create(['name' => 'Coworking Space', 'slug' => 'coworking-space']);
        }

        $meeting = Category::where('name', 'Meeting Room')->first();
        if (!$meeting) {
            $meeting = Category::create(['name' => 'Meeting Room', 'slug' => 'meeting-room']);
        }

        $office = Category::where('name', 'Office Space')->first();
        if (!$office) {
            $office = Category::create(['name' => 'Office Space', 'slug' => 'office-space']);
        }

        $lagos = City::where('name', 'Lagos')->first();
        if (!$lagos) {
            $lagos = City::create(['name' => 'Lagos', 'slug' => 'lagos']);
        }

        $abuja = City::where('name', 'Abuja')->first();
        if (!$abuja) {
            $abuja = City::create(['name' => 'Abuja', 'slug' => 'abuja']);
        }

        $portharcourt = City::where('name', 'Port Harcourt')->first();
        if (!$portharcourt) {
            $portharcourt = City::create(['name' => 'Port Harcourt', 'slug' => 'port-harcourt']);
        }

        // Create sample listings
        $listings = [
            // Featured Listings
            [
                'name' => 'Premium Coworking Hub - Victoria Island',
                'address' => '123 Adeola Odeku Street, Victoria Island, Lagos',
                'description' => 'Modern coworking space with premium amenities, high-speed internet, and meeting rooms.',
                'price_range' => '₦5,000 - ₦15,000/day',
                'contact_phone' => '+2348000000011',
                'whatsapp_number' => '+2348000000011',
                'featured' => true,
                'featured_request_status' => 'active',
                'featured_plan' => 'premium',
                'featured_duration' => 3,
                'featured_amount' => 36000,
                'featured_starts_at' => now(),
                'featured_expires_at' => now()->addMonths(3),
                'category_id' => $coworking->id,
                'city_id' => $lagos->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Executive Meeting Center - Ikoyi',
                'address' => '45 Ikoyi Crescent, Ikoyi, Lagos',
                'description' => 'Executive meeting rooms with video conferencing facilities.',
                'price_range' => '₦10,000 - ₦25,000/day',
                'contact_phone' => '+2348000000012',
                'whatsapp_number' => '+2348000000012',
                'featured' => true,
                'featured_request_status' => 'active',
                'featured_plan' => 'featured',
                'featured_duration' => 1,
                'featured_amount' => 5000,
                'featured_starts_at' => now(),
                'featured_expires_at' => now()->addMonth(),
                'category_id' => $meeting->id,
                'city_id' => $lagos->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Business Center - Maitama',
                'address' => '78 Maitama District, Abuja',
                'description' => 'Full-service business center with conference facilities.',
                'price_range' => '₦8,000 - ₦20,000/day',
                'contact_phone' => '+2348000000013',
                'whatsapp_number' => '+2348000000013',
                'featured' => true,
                'featured_request_status' => 'active',
                'featured_plan' => 'premium',
                'featured_duration' => 2,
                'featured_amount' => 24000,
                'featured_starts_at' => now(),
                'featured_expires_at' => now()->addMonths(2),
                'category_id' => $office->id,
                'city_id' => $abuja->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],

            // Regular Listings in Lagos
            [
                'name' => 'Creative Workspace - Yaba',
                'address' => '15 Herbert Macaulay Way, Yaba, Lagos',
                'description' => 'Creative workspace for tech startups and entrepreneurs.',
                'price_range' => '₦3,000 - ₦8,000/day',
                'contact_phone' => '+2348000000014',
                'whatsapp_number' => '+2348000000014',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $lagos->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Quiet Office - Lekki',
                'address' => '200 Lekki-Epe Expressway, Lekki, Lagos',
                'description' => 'Quiet office space perfect for focused work.',
                'price_range' => '₦4,000 - ₦10,000/day',
                'contact_phone' => '+2348000000015',
                'whatsapp_number' => '+2348000000015',
                'featured' => false,
                'category_id' => $office->id,
                'city_id' => $lagos->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Team Meeting Room - Surulere',
                'address' => '50 Bode Thomas Street, Surulere, Lagos',
                'description' => 'Perfect for team meetings and brainstorming sessions.',
                'price_range' => '₦2,500 - ₦7,000/day',
                'contact_phone' => '+2348000000016',
                'whatsapp_number' => '+2348000000016',
                'featured' => false,
                'category_id' => $meeting->id,
                'city_id' => $lagos->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],

            // Regular Listings in Abuja
            [
                'name' => 'Government District Office - Abuja',
                'address' => '25 Shehu Shagari Way, Abuja',
                'description' => 'Professional office space near government offices.',
                'price_range' => '₦6,000 - ₦12,000/day',
                'contact_phone' => '+2348000000017',
                'whatsapp_number' => '+2348000000017',
                'featured' => false,
                'category_id' => $office->id,
                'city_id' => $abuja->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Co-working Hub - Wuse 2',
                'address' => '100 Wuse 2, Abuja',
                'description' => 'Modern co-working space with great networking opportunities.',
                'price_range' => '₦3,500 - ₦9,000/day',
                'contact_phone' => '+2348000000018',
                'whatsapp_number' => '+2348000000018',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $abuja->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Training Room - Garki',
                'address' => '75 Garki Area 1, Abuja',
                'description' => 'Spacious training room with presentation equipment.',
                'price_range' => '₦4,000 - ₦8,000/day',
                'contact_phone' => '+2348000000019',
                'whatsapp_number' => '+2348000000019',
                'featured' => false,
                'category_id' => $meeting->id,
                'city_id' => $abuja->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],

            // Regular Listings in Port Harcourt
            [
                'name' => 'Rivers State Business Center',
                'address' => '10 Aba Road, Port Harcourt',
                'description' => 'Professional business center in the heart of Port Harcourt.',
                'price_range' => '₦3,000 - ₦7,000/day',
                'contact_phone' => '+2348000000020',
                'whatsapp_number' => '+2348000000020',
                'featured' => false,
                'category_id' => $office->id,
                'city_id' => $portharcourt->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Garden Workspace - Port Harcourt',
                'address' => '35 Old GRA, Port Harcourt',
                'description' => 'Peaceful garden workspace for creative professionals.',
                'price_range' => '₦2,500 - ₦6,000/day',
                'contact_phone' => '+2348000000021',
                'whatsapp_number' => '+2348000000021',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $portharcourt->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Board Room - Trans Amadi',
                'address' => '5 Trans Amadi, Port Harcourt',
                'description' => 'Executive board room with all amenities.',
                'price_range' => '₦5,000 - ₦12,000/day',
                'contact_phone' => '+2348000000022',
                'whatsapp_number' => '+2348000000022',
                'featured' => false,
                'category_id' => $meeting->id,
                'city_id' => $portharcourt->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],

            // More listings to have enough for pagination
            [
                'name' => 'Tech Hub - Ikeja',
                'address' => '200 Ikeja GRA, Lagos',
                'description' => 'Technology-focused co-working space.',
                'price_range' => '₦4,000 - ₦9,000/day',
                'contact_phone' => '+2348000000023',
                'whatsapp_number' => '+2348000000023',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $lagos->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Executive Suite - Victoria Island',
                'address' => '500 Victoria Island, Lagos',
                'description' => 'Luxury executive suite with ocean view.',
                'price_range' => '₦15,000 - ₦30,000/day',
                'contact_phone' => '+2348000000024',
                'whatsapp_number' => '+2348000000024',
                'featured' => false,
                'category_id' => $office->id,
                'city_id' => $lagos->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Startup Hub - Yaba',
                'address' => '100 Yaba Tech Hub, Lagos',
                'description' => 'Affordable space for early-stage startups.',
                'price_range' => '₦1,500 - ₦4,000/day',
                'contact_phone' => '+2348000000025',
                'whatsapp_number' => '+2348000000025',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $lagos->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],
            [
                'name' => 'Conference Center - Maitama',
                'address' => '200 Maitama, Abuja',
                'description' => 'Large conference center for events.',
                'price_range' => '₦20,000 - ₦50,000/day',
                'contact_phone' => '+2348000000026',
                'whatsapp_number' => '+2348000000026',
                'featured' => false,
                'category_id' => $meeting->id,
                'city_id' => $abuja->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Shared Office - Wuse',
                'address' => '150 Wuse Zone 5, Abuja',
                'description' => 'Shared office space with flexible terms.',
                'price_range' => '₦3,000 - ₦6,000/day',
                'contact_phone' => '+2348000000027',
                'whatsapp_number' => '+2348000000027',
                'featured' => false,
                'category_id' => $office->id,
                'city_id' => $abuja->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Creative Studio - Port Harcourt',
                'address' => '25 Rumuokwuta, Port Harcourt',
                'description' => 'Creative studio for designers and artists.',
                'price_range' => '₦2,000 - ₦5,000/day',
                'contact_phone' => '+2348000000028',
                'whatsapp_number' => '+2348000000028',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $portharcourt->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],
            [
                'name' => 'Business Lounge - Ikoyi',
                'address' => '75 Ikoyi, Lagos',
                'description' => 'Premium business lounge with meeting facilities.',
                'price_range' => '₦7,000 - ₦15,000/day',
                'contact_phone' => '+2348000000029',
                'whatsapp_number' => '+2348000000029',
                'featured' => false,
                'category_id' => $office->id,
                'city_id' => $lagos->id,
                'user_id' => $host1->id,
                'status' => 'published',
            ],
            [
                'name' => 'Training Facility - Jabi',
                'address' => '50 Jabi Lake, Abuja',
                'description' => 'Modern training facility with AV equipment.',
                'price_range' => '₦5,000 - ₦12,000/day',
                'contact_phone' => '+2348000000030',
                'whatsapp_number' => '+2348000000030',
                'featured' => false,
                'category_id' => $meeting->id,
                'city_id' => $abuja->id,
                'user_id' => $host2->id,
                'status' => 'published',
            ],
            [
                'name' => 'Co-working Space - GRA Port Harcourt',
                'address' => '80 Old GRA, Port Harcourt',
                'description' => 'Collaborative co-working space in Port Harcourt GRA.',
                'price_range' => '₦3,500 - ₦8,000/day',
                'contact_phone' => '+2348000000031',
                'whatsapp_number' => '+2348000000031',
                'featured' => false,
                'category_id' => $coworking->id,
                'city_id' => $portharcourt->id,
                'user_id' => $host3->id,
                'status' => 'published',
            ],
        ];

        foreach ($listings as $listingData) {
            $listingData['slug'] = Str::slug($listingData['name']) . '-' . uniqid();
            Listing::create($listingData);
        }

        $this->command->info('Sample listings created successfully!');
    }
}
