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
        $hostUser = User::where('email', 'host@gridspace.com')->first();
        $category = Category::first();
        $city = City::first();

        if ($hostUser && $category && $city) {
            // Create sample listings for the host
            $listings = [
                [
                    'name' => 'Modern Co-working Space in Lagos',
                    'description' => 'A modern and fully-equipped co-working space perfect for freelancers and small teams. High-speed internet, meeting rooms, and 24/7 access.',
                    'address' => '123 Victoria Island, Lagos',
                    'contact_phone' => '+2348000000002',
                    'whatsapp_number' => '+2348000000002',
                    'website' => 'https://example.com',
                    'price_range' => '₦15,000 - ₦25,000 per month',
                    'capacity' => 25,
                    'status' => 'published',
                    'featured' => true,
                ],
                [
                    'name' => 'Private Office in Lekki',
                    'description' => 'Private office space with dedicated desk, storage, and meeting room access. Perfect for small businesses and startups.',
                    'address' => '45 Lekki Phase 1, Lagos',
                    'contact_phone' => '+2348000000002',
                    'whatsapp_number' => '+2348000000002',
                    'website' => null,
                    'price_range' => '₦30,000 - ₦50,000 per month',
                    'capacity' => 10,
                    'status' => 'published',
                    'featured' => false,
                ],
                [
                    'name' => 'Creative Hub in Ikorodu',
                    'description' => 'Creative workspace designed for artists, designers, and creative professionals. Includes studio space and exhibition areas.',
                    'address' => '78 Ikorodu Road, Lagos',
                    'contact_phone' => '+2348000000002',
                    'whatsapp_number' => '+2348000000002',
                    'website' => null,
                    'price_range' => '₦10,000 - ₦20,000 per month',
                    'capacity' => 15,
                    'status' => 'draft',
                    'featured' => false,
                ],
            ];

            foreach ($listings as $listingData) {
                Listing::create([
                    'user_id' => $hostUser->id,
                    'category_id' => $category->id,
                    'city_id' => $city->id,
                    'slug' => Str::slug($listingData['name']),
                    'name' => $listingData['name'],
                    'description' => $listingData['description'],
                    'address' => $listingData['address'],
                    'contact_phone' => $listingData['contact_phone'],
                    'whatsapp_number' => $listingData['whatsapp_number'],
                    'website' => $listingData['website'],
                    'price_range' => $listingData['price_range'],
                    'capacity' => $listingData['capacity'],
                    'status' => $listingData['status'],
                    'featured' => $listingData['featured'],
                ]);
            }

            echo "Created 3 sample listings for host@gridspace.com\n";
        } else {
            echo "Could not find host user, category, or city\n";
        }
    }
}
